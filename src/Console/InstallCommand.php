<?php

namespace Triyatna\SmmLaravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'ty-smm:install';
    protected $description = 'Install the SMM Laravel package';

    public function handle(): void
    {
        $this->info('Installing SMM Laravel Package...');

        $this->info('Publishing configuration...');
        $this->call('vendor:publish', [
            '--provider' => "Triyatna\SmmLaravel\SmmServiceProvider",
            '--tag' => "smm-config"
        ]);

        $this->info('Updating .env file...');
        $this->addEnvironmentVariables();

        $this->info('SMM Laravel package installed successfully.');
        $this->comment('Please add your API ID and Key to your .env file.');
    }

    protected function addEnvironmentVariables(): void
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContent = File::get($envPath);

            $variables = [
                'SMM_API_URL' => 'SMM_API_URL=https://irvankedesmm.co.id/api',
                'SMM_API_ID' => 'SMM_API_ID=',
                'SMM_API_KEY' => 'SMM_API_KEY=',
            ];

            $newContent = $envContent;
            $needsNewline = !str_ends_with($newContent, "\n");

            foreach ($variables as $key => $value) {
                if (strpos($newContent, $key) === false) {
                    if ($needsNewline) {
                        $newContent .= "\n";
                        $needsNewline = false;
                    }
                    $newContent .= "\n" . $value;
                }
            }

            if ($newContent !== $envContent) {
                File::put($envPath, $newContent);
                $this->info('.env file updated with SMM variables.');
            } else {
                $this->info('SMM variables already exist in .env file.');
            }
        }
    }
}
