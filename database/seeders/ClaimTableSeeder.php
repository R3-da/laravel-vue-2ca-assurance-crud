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

        $statusOptions = ['Open', 'In Progress', 'Resolved', 'Closed'];
        $categories = ['Refund', 'Contract Issue', 'Billing Error', 'Other'];
        $descriptions = [
            'A detailed issue description for claim.',
            'Client is requesting a refund due to an error.',
            'The contract has an issue that needs addressing.',
            'A billing error was identified by the client.',
            'The client is reporting an unknown issue.',
        ];

        // Generate 10 claims with random values
        for ($i = 0; $i < 10; $i++) {
            $client = $clients->random(); // Randomly pick a client
            $status = $statusOptions[array_rand($statusOptions)]; // Random status from available options
            $category = $categories[array_rand($categories)]; // Random category
            $description = $descriptions[array_rand($descriptions)]; // Random description

            Claim::create([
                'subject' => 'Issue reported by ' . $client->name,
                'detailed_description' => $description,
                'category' => $category,
                'status' => $status,
                'user_id' => $client->id,
                'broker_id' => $brokers->isNotEmpty() ? $brokers->random()->id : null, // Random broker if available
            ]);
        }
    }
}