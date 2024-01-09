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

    public function checkUserStatus($phone)
    {
        $client = new Client();
        $response = $client->post($this->baseUrl . 'uzum/buyer/check-status', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken
            ],
            'form_params' => [
                'phone' => $phone
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createContract($contractData)
    {
        // Implement the logic to create a contract
    }

    public function confirmContract($contractId)
    {
        // Implement the logic to confirm a contract
    }

    // Additional methods to interact with the API...
}