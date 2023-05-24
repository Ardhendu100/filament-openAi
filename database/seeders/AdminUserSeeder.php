<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'RedQueenIVFAI Admin',
            'email' => 'admin@redqueenivfai.com',
            'username' => 'admin.redqueenivfai',
            'password' => Hash::make(sha1(uniqid().microtime().uniqid())),
            'api_key' => sha1(uniqid().microtime().uniqid()),
            'api_secret' => sha1(uniqid().microtime().uniqid()),
        ])->assignRole('super-admin');
    }
}
