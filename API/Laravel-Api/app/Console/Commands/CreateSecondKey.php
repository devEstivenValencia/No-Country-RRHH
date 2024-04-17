<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSecondKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-second-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a second key on .env file to encrypt comunication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rand = random_bytes(32);
        $key = base64_encode($rand);
        $this->putPermanentEnv("SECOND_KEY", $key);
    }

    private function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
