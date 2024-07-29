<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User Mobile RSKI',
            'email' => 'usermobilerski',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'User Web RSKI',
            'email' => 'userwebrski',
            'password' => Hash::make('12345678'),
        ]);
    }
}
