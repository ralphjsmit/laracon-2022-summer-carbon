<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Ralph J. Smit',
                'email' => 'rjs@ralphjsmit.com',
                'password' => Hash::make('secret'),
            ]);

        Author::factory(5)
            ->create();

        Post::factory(128)
            ->create();

        Post::factory(4)
            ->set('published_at', null)
            ->create();
    }
}
