<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PassportGenerateSecrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:generate-secrets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Passport secrets for .env file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $passwordGrantSecret = Str::random(40);
        $personalAccessSecret = Str::random(40);
        
        $this->info('Add the following lines to your .env file:');
        $this->newLine();
        $this->line("PASSPORT_PASSWORD_GRANT_SECRET=$passwordGrantSecret");
        $this->line("PASSPORT_PERSONAL_ACCESS_SECRET=$personalAccessSecret");
        $this->newLine();
        $this->info('These values are required for Passport authentication to work properly.');
    }
}