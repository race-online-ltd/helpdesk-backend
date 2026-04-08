<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RaceService implements TokenServiceInterface
{
    public function generateToken()
    {
        $url = 'http://race.prismerp.net/auth/accesstoken/';
        $payload = [
            'appname' => 'RaceOnline',
            'client_id' => 'xyaLvLfC6iLvGLWI',
            'client_secret' => 'HGxSx0aTCO1fCWPh'
        ];
        $response = Http::asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Race has failed to token genarate!' . $response->body());
        }

        return [$response->json()['access_token']];
    }

    public function loginWithToken(string $token)
    {
        $url = 'http://race.prismerp.net/auth/apilogin/';
        $payload = [
            'appname' => 'RaceOnline',
            'client_id' => 'xyaLvLfC6iLvGLWI',
            'client_secret' => 'HGxSx0aTCO1fCWPh',
            'status' => 'Success',
            'access_token' => $token
        ];
        $response = Http::asForm()->post($url, $payload);

        if ($response->failed()) {
            Log::info('Race Login Failed!' . $response->body());
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
