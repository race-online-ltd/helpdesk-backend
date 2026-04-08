<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DhakaColoService implements TokenServiceInterface
{

    public function generateToken()
    {
        $url = 'http://dhakacolo.prismerp.net/auth/accesstoken/';
        $payload = [
            'appname' => 'DhakaColo',
            'client_id' => '8MDkMtAMLoFJiMEu',
            'client_secret' => 'HPVav1uumHx6l1E0'
        ];
        // $response = Http::asForm()->post($url, $payload);
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token()
        ])->asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Dhaka Colo has failed to token genarate!' . $response->body());
        }
        return $response->headers();
        return [$response->json()['access_token']];
    }

    public function loginWithToken(string $token)
    {
        $url = 'http://dhakacolo.prismerp.net/auth/apilogin/';
        $payload = [
            'appname' => 'DhakaColo',
            'client_id' => '8MDkMtAMLoFJiMEu',
            'client_secret' => 'HPVav1uumHx6l1E0',
            'status' => 'Success',
            'access_token' => $token
        ];
        $response = Http::asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Dhaka Colo Login Failed!' . $response->body());
        }

        $cookies = $response->headers()['Set-Cookie'] ?? [];

        $sessionid = null;
        foreach ($cookies as $cookie) {
            if (strpos($cookie, 'sessionid=') !== false) {
                preg_match('/sessionid=([^;]+)/', $cookie, $matches);
                $sessionid = $matches[1] ?? null;
                break;
            }
        }

        return $sessionid;
    }
}
