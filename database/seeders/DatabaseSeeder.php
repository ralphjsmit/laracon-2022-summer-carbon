<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Ralph J. Smit',
            'email' => 'rjs@ralphjsmit.com',
            'password' => Hash::make('secret'),
        ]);

        Post::factory(12)
            ->create();

        Post::factory(4)
            ->set('published_at', null)
            ->create();
    }
}
