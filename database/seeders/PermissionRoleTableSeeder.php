<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionRole = [];

        // Add claims-related permissions to client and broker
        // Client role gets 'claims-view' and 'claims-create'
        $clientRoleId = 3; // Assuming the role ID for Client is 3
        $brokerRoleId = 2; // Assuming the role ID for Broker is 2

        $permissionRole[] = ['role_id' => $clientRoleId, 'permission_id' => 2, 'created_at' => now()]; // claims-view
        $permissionRole[] = ['role_id' => $clientRoleId, 'permission_id' => 3, 'created_at' => now()]; // claims-create

        $permissionRole[] = ['role_id' => $brokerRoleId, 'permission_id' => 2, 'created_at' => now()]; // claims-view
        $permissionRole[] = ['role_id' => $brokerRoleId, 'permission_id' => 4, 'created_at' => now()]; // claims-edit

        // Add the rest of the permissions to role_id 1 (admin)
        for ($i = 1; $i <= 26; $i++) {
            $permissionRole[] = [
                'role_id' => 1, // Assuming 1 is for Admin
                'permission_id' => $i,
                'created_at' => now(),
            ];
        }

        // Insert into the pivot table
        DB::table('permission_role')->insert($permissionRole);
    }
}