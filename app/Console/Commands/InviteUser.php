<?php

namespace App\Console\Commands;

use App\Models\Invite;
use Illuminate\Console\Command;

class InviteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cayenne:invite {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an invite to a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        Invite::create([
            'email' => $email,
            'code' => str()->random(24),
        ])->send();

        $this->info("Sent invite to \"{$email}\".");
    }
}
