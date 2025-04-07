<?php

namespace Tests\Feature\API;

use App\Models\Trainer;
use App\Models\User;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // Seed the roles and permissions
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                     'access_token'
                 ]);
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Verify that the user has the trainer role by default
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('trainer'));
        
        // Verify that a trainer was created for the user
        $this->assertDatabaseHas('trainers', [
            'user_id' => $user->id,
            'name' => 'Test User',
        ]);
    }

    /** @test */
    public function a_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'pass',
            'password_confirmation' => 'word',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $user->assignRole('trainer');
        
        // Create a trainer for this user
        Trainer::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                     'access_token'
                 ]);
    }

    /** @test */
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials'
                 ]);
    }

    /** @test */
    public function a_user_can_logout()
    {
        $user = User::factory()->create();
        $user->assignRole('trainer');
        
        Passport::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Successfully logged out'
                 ]);
    }
    
    /** @test */
    public function admin_registration_does_not_create_trainer()
    {
        // Admin via direct registration (assuming we have an admin registration endpoint or flag)
        $response = $this->postJson('/api/register', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin'
        ]);

        $response->assertStatus(201);
        
        $user = User::where('email', 'admin@example.com')->first();
        $this->assertTrue($user->hasRole('admin'));
        
        // Verify no trainer was created for admin
        $this->assertDatabaseMissing('trainers', [
            'user_id' => $user->id,
        ]);
    }
    
    /** @test */
    public function trainer_role_has_necessary_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('trainer');
        
        // Verify that the trainer role has the appropriate permissions
        $this->assertTrue($user->hasPermissionTo('trainers.view'));
        $this->assertTrue($user->hasPermissionTo('pokemons.view'));
        $this->assertTrue($user->hasPermissionTo('pokemons.update'));
        $this->assertTrue($user->hasPermissionTo('battles.view'));
        $this->assertTrue($user->hasPermissionTo('battles.create'));
    }
}
