<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Regenerate Passport keys
        Artisan::call('passport:keys', ['--force' => true]);
        
        // Ensure tables exist
        if (!Schema::hasTable('oauth_clients') || !Schema::hasTable('oauth_personal_access_clients')) {
            return;
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing clients
        DB::table('oauth_clients')->truncate();
        DB::table('oauth_personal_access_clients')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create personal access client
        $personalAccessClient = Client::create([
            'name' => 'Pokemon League Personal Access Client',
            'secret' => env('PASSPORT_PERSONAL_ACCESS_SECRET', 'personal-access-secret'),
            'provider' => 'users',
            'redirect' => env('APP_URL'),
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create password grant client
        $passwordGrantClient = Client::create([
            'name' => 'Pokemon League Password Grant Client',
            'secret' => env('PASSPORT_PASSWORD_GRANT_SECRET', 'password-grant-secret'),
            'provider' => 'users',
            'redirect' => env('APP_URL'),
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Register personal access client
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $personalAccessClient->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}