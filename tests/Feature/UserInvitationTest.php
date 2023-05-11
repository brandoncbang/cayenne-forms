<?php

namespace Tests\Feature;

use App\Mail\InviteEmail;
use App\Models\Invite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserInvitationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_be_invited()
    {
        Mail::fake();

        $this
            ->artisan('cayenne:invite', ['email' => 'johndoe@example.com'])
            ->expectsOutput('Sent invite to "johndoe@example.com".')
            ->assertExitCode(0);

        $this->assertDatabaseCount(Invite::class, 1);

        $invite = Invite::first();
        $this->assertEquals('johndoe@example.com', $invite->email);
        $this->assertEquals(24, str($invite->code)->length());

        Mail::assertSent(InviteEmail::class, function ($mail) use ($invite) {
            return $mail->hasTo('johndoe@example.com')
                && $mail->invite->is($invite);
        });
    }
}
