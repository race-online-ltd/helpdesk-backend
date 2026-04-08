<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EarthService implements TokenServiceInterface
{
    public function generateToken()
    {
        $url = 'http://earth.prismerp.net/auth/accesstoken/';
        $payload = [
            'appname' => 'EarthComm',
            'client_id' => 'VhwcGKpxfgwwgTyF',
            'client_secret' => '7GWEirQnTkWb6uV7'
        ];
        $response = Http::asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Earth has failed to token genarate!' . $response->body());
        }

        return [$response->json()['access_token']];
    }

    public function loginWithToken(string $token)
    {
        $url = 'http://earth.prismerp.net/auth/apilogin/';
        $payload = [
            'appname' => 'EarthComm',
            'client_id' => 'VhwcGKpxfgwwgTyF',
            'client_secret' => '7GWEirQnTkWb6uV7',
            'status' => 'Success',
            'access_token' => $token
        ];
        $response = Http::asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Earth Login Failed!' . $response->body());
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
