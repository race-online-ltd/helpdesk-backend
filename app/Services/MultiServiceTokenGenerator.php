<?php

namespace App\Services;

class MultiServiceTokenGenerator implements TokenServiceInterface
{
    protected $raceService;
    protected $earthService;
    protected $dhakaColoService;

    public function __construct(RaceService $raceService, EarthService $earthService, DhakaColoService $dhakaColoService)
    {
        $this->raceService = $raceService;
        $this->earthService = $earthService;
        $this->dhakaColoService = $dhakaColoService;
    }

    public function generateToken()
    {
        $raceToken = $this->raceService->generateToken();
        $earthToken = $this->earthService->generateToken();
        $dhakaColoToken = $this->dhakaColoService->generateToken();

        return [
            'Race' => $raceToken,
            'Earth' => $earthToken,
            'DhakaColo' => $dhakaColoToken,
        ];
    }

    public function loginWithToken(string $token)
    {
        $raceToken = $this->raceService->loginWithToken($token);
        $earthToken = $this->earthService->loginWithToken($token);
        $dhakaColoToken = $this->dhakaColoService->loginWithToken($token);
        // return $raceToken && $earthToken && $dhakaColoToken;
        return  [
            'Race' => $raceToken,
            'Earth' => $earthToken,
            'DhakaColo' => $dhakaColoToken,
        ];
    }
}
