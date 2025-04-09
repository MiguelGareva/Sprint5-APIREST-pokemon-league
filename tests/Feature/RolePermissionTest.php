<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RolePermissionSeeder;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test if roles are created correctly.
     * 
     * @return void
     */
    public function test_roles_are_created()
    {
        //Run the Seeder
        $this->seed(RolePermissionSeeder::class);

        //Check if all roler are created
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'trainer']);
        $this->assertDatabaseHas('roles', ['name' => 'guest']);

        //Check if the number of roles is correct
        $this->assertEquals(3, Role::count());
    }

    /**
     * Test if all permissions are created correctly.
     * 
     * @return void
     */
    public function test_permissions_are_created(){
        //Run the Seeder
        $this->seed(RolePermissionSeeder::class);

        //Check if specific permissions exist
        $this->assertDatabaseHas('permissions', ['name' => 'trainers.view']);
        $this->assertDatabaseHas('permissions', ['name' => 'traines.create']);
        $this->assertDatabaseHas('permissions', ['name' => 'trainers.update']);
        $this->assertDatabaseHas('permissions', ['name' => 'trainers.delete']);
        $this->assertDatabaseHas('permissions', ['name' => 'pokemons.view']);
        $this->assertDatabaseHas('permissions', ['name' => 'pokemons.create']);
        $this->assertDatabaseHas('permissions', ['name' => 'pokemons.update']);
        $this->assertDatabaseHas('permissions', ['name' => 'pokemons.delete']);
        $this->assertDatabaseHas('permissions', ['name' => 'battles.view']);
        $this->assertDatabaseHas('permissions', ['name' => 'battles.create']);
        $this->assertDatabaseHas('permissions', ['name' => 'battles.delete']);

        //Check if the number of permissions is correct
        $this->assertEquals(11, Permission::count());
    }
    /**
     * Test if permissions are correctly assigned to roles
     * 
     * @return void
     */
    public function test_roles_have_correct_permissions(){
        //Run the seeder
        $this->seed(RolePermissionSeeder::class);

        //Check admin role has all permissions
        $adminRole = Role::findByName('admin');
        $this->assertEquals(11, $adminRole->permissions->count());

        // Check trainer role has the correct permisssions
        $trainerRole = Role::findByName('trainer');
        $this->assertTrue($trainerRole->hasPermissionTo('trainers.view'));
        $this->assertTrue($trainerRole->hasPermissionTo('pokemons.view'));
        $this->assertTrue($trainerRole->hasPermissionTo('pokemons.update'));
        $this->assertTrue($trainerRole->hasPermissionTo('battles.view'));
        $this->assertTrue($trainerRole->hasPermissionTo('battles.create'));
        $this->assertFalse($trainerRole->hasPermissionTo('trainers.delete'));
        $this->assertEquals(5, $trainerRole->permissions->count());

        //Check guest rola has the correct permissions
        $guestRole = Role::findByName('guest');
        $this->assertTrue($guestRole->hasPermissionTo('trainers.view'));
        $this->assertTrue($guestRole->hasPermissionTo('pokemons.view'));
        $this->assertTrue($guestRole->hasPermissionTo('battles.view'));
        $this->assertFalse($guestRole->hasPermissionTo('pokemons.create'));
        $this->assertEquals(3, $guestRole->permissions->count());
    }
}
