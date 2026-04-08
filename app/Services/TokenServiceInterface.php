<?php

namespace App\Services;

use Illuminate\Support\Arr;

interface TokenServiceInterface
{
    public function generateToken();
    public function loginWithToken(string $token);
}
