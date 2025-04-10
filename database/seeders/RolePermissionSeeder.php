<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'trainers.view',
            'trainers.create',
            'trainers.update',
            'trainers.delete',
            'pokemons.view',
            'pokemons.create',
            'pokemons.update',
            'pokemons.delete',
            'battles.view',
            'battles.create',
            'battles.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Trainer role
        $trainerRole = Role::create(['name' => 'trainer']);
        $trainerRole->givePermissionTo([
            'trainers.view',
            'pokemons.view',
            'pokemons.update',
            'battles.view',
            'battles.create'
        ]);

        // Guest role
        $guestRole = Role::create(['name' => 'guest']);
        $guestRole->givePermissionTo([
            'trainers.view',
            'pokemons.view',
            'battles.view'
        ]);
    }
}