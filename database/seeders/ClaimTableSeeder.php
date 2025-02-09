<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Claim;
use App\Models\User;
use App\Models\Role;

class ClaimTableSeeder extends Seeder
{
    public function run()
    {
        $clientRole = Role::where('name', 'client')->first();
        $brokerRole = Role::where('name', 'broker')->first();

        $clients = User::whereHas('roles', function ($query) use ($clientRole) {
            $query->where('role_id', $clientRole->id);
        })->get();

        $brokers = User::whereHas('roles', function ($query) use ($brokerRole) {
            $query->where('role_id', $brokerRole->id);
        })->get();

        foreach ($clients as $client) {
            Claim::create([
                'name' => 'Claim for ' . $client->name,
                'description' => 'Description for claim by ' . $client->name,
                'status' => 'pending',
                'user_id' => $client->id,
                'broker_id' => $brokers->random()->id,
            ]);
        }
    }
}