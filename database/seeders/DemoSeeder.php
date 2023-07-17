<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seed the database with Forms, Entries, & a default User to login as.
 */
class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        Form::factory()
            ->count(18)
            ->for($user)
            ->create()
            ->each(function ($form) {
                Entry::factory()
                    ->count(fake()->randomNumber(2))
                    ->for($form)
                    ->create();

                Entry::factory()
                    ->count(fake()->randomNumber(2))
                    ->for($form)
                    ->archived()
                    ->create();

                Entry::factory()
                    ->count(fake()->randomNumber(2))
                    ->for($form)
                    ->trashed()
                    ->create();
            });

        Form::factory()
            ->for($user)
            ->create([
                'name' => 'Demo Form',
                'success_url' => '/demo/success',
            ]);
    }
}
