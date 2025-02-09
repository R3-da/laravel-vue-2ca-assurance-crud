<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            [
                'name' => 'Mr. Admin',
                'email' => 'admin@admin.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Broker',
                'email' => 'broker@broker.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Client',
                'email' => 'client@client.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Admin 1',
                'email' => 'admin1@admin.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Client 1',
                'email' => 'client1@user.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Client 2',
                'email' => 'client2@user.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Broker 1',
                'email' => 'broker1@broker.com',
                'password' => $password,
                'created_at' => now(),
            ],
            [
                'name' => 'Mr. Broker 2',
                'email' => 'broker2@broker.com',
                'password' => $password,
                'created_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
