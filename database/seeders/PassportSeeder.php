<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar clientes existentes para evitar duplicados
        DB::table('oauth_clients')->truncate();
        
        // Crear el cliente de tipo "Password Grant" para la autenticación de la API
        Client::create([
            'id' => 1,
            'name' => 'Pokemon League Password Grant Client',
            'secret' => env('PASSPORT_PASSWORD_GRANT_SECRET'),
            'provider' => 'users',
            'redirect' => env('APP_URL'),
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
        ]);
        
        // Crear el cliente de tipo "Personal Access" para tokens personales
        Client::create([
            'id' => 2,
            'name' => 'Pokemon League Personal Access Client',
            'secret' => env('PASSPORT_PERSONAL_ACCESS_SECRET'),
            'provider' => 'users',
            'redirect' => env('APP_URL'),
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
        ]);
        
        // Registrar el cliente personal access en la tabla oauth_personal_access_clients
        DB::table('oauth_personal_access_clients')->truncate();
        DB::table('oauth_personal_access_clients')->insert([
            'id' => 1,
            'client_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}