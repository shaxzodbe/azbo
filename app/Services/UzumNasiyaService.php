<?php

namespace App\Services;

use GuzzleHttp\Client;

class UzumNasiyaService
{
    protected $baseUrl = 'https://cabinet.paymart.uz/api/v3/';
    protected $bearerToken;

    public function __construct()
    {
        $this->bearerToken = config('services.uzum_nasiya.bearer_token');
    }

    public function checkUserStatus($phone, $callbackUrl)
    {
        $client = new Client();
        $response = $client->post($this->baseUrl . 'uzum/buyer/check-status?callback=' . $callbackUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'phone' => $phone
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function calculate($data)
    {
        $client = new Client();
        $response = $client->post('https://tera.paymart.uz/api/v3/mfo/calculate', [
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function confirmContract($contractId)
    {
        // Implement the logic to confirm a contract
    }

    // Additional methods to interact with the API...
}