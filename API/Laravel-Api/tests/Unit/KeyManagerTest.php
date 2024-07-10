<?php

namespace Tests\Unit;

use App\Models\KeyManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KeyManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $rand = random_bytes(32);
        $keyManager = new KeyManager();
        $keyManager->id = Str::uuid(36);
        $keyManager->key = base64_encode($rand);
        $keyManager->expires_at = Carbon::now()->addMinutes(7);
        $keyManager->save();
        $this->assertTrue(true);
    }
}
