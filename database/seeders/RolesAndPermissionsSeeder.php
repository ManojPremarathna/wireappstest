<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission::create(['name' => 'medication.index']); // all users can then no need to check
        // Permission::create(['name' => 'medication.show']); // all users can then no need to check
        // Permission::create(['name' => 'medication.update']); // cashier, manager, owner
        
        Permission::create(['name' => 'medication.store']); // only owner
        Permission::create(['name' => 'medication.destroy']); // soft delete // owner and manager
        Permission::create(['name' => 'medication.delete']); // only owner
        
        Permission::create(['name' => 'customer.store']); // only owner
        Permission::create(['name' => 'customer.destroy']); // soft delete // owner and manager
        Permission::create(['name' => 'customer.delete']); // only owner

        $role = Role::create(['name' => 'owner'])
            ->givePermissionTo(Permission::all());
        
        $role = Role::create(['name' => 'manager'])
            ->givePermissionTo(['medication.destroy', 'customer.destroy']);
        
        $role = Role::create(['name' => 'cashier']);
    }
}
