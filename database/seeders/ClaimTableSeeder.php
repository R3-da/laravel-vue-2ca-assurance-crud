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

        $clients = User::whereHas('roles', fn($query) => $query->where('role_id', $clientRole->id))->get();
        $brokers = User::whereHas('roles', fn($query) => $query->where('role_id', $brokerRole->id))->get();

        foreach ($clients as $client) {
            Claim::create([
                'subject' => 'Issue reported by ' . $client->name,
                'detailed_description' => 'This is a detailed description for ' . $client->name . "'s claim.",
                'category' => ['Refund', 'Contract Issue', 'Billing Error', 'Other'][array_rand(['Refund', 'Contract Issue', 'Billing Error', 'Other'])],
                'status' => 'Open',
                'user_id' => $client->id,
                'broker_id' => $brokers->isNotEmpty() ? $brokers->random()->id : null,
            ]);
        }
    }
}