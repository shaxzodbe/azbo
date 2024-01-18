<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request as Psr7Request;

class UzumNasiyaService
{
    protected $baseUrl = 'https://cabinet.paymart.uz/api/v3/';
    protected $bearerToken;

    public function __construct()
    {
        $this->bearerToken = config('services.uzum_nasiya.bearer_token');
    }

    public function checkUserStatus($phone, $callbackUrl = null)
    {
        $client = new Client();
        $url = $this->baseUrl . 'uzum/buyer/check-status';

        if ($callbackUrl) {
            $url .= '?callback=' . urlencode($callbackUrl);
        }
        $response = $client->post($url, [
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

    public function calculate($userId, array $products)
    {
        $client = new Client();
        $url = $this->baseUrl . 'mfo/calculate';

        $response = $client->post($url, [
          'headers' => [
            'Authorization' => 'Bearer ' . $this->bearerToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'json' => [
            'user_id' => $userId,
            'products' => $products
          ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createOrder($userId, $period, $callback, array $products)
    {
        $client = new Client();
        $url = $this->baseUrl . 'mfo/order';

        $response = $client->post($url, [
          'headers' => [
            'Authorization' => 'Bearer ' . $this->bearerToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'json' => [
            'user_id' => $userId,
            'period' => $period,
            'callback' => $callback,
            'products' => $products
          ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function confirmContract($contractId)
    {
        $client = new Client();
        $url = $this->baseUrl . 'uzum/contract-confirm';

        $response = $client->post($url, [
          'headers' => [
            'Authorization' => 'Bearer ' . $this->bearerToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'json' => [
            'contract_id' => $contractId
          ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function cancelContract($contractId, $buyerPhone)
    {
        $client = new Client();
        $url = $this->baseUrl . 'buyers/credit/cancel';

        $response = $client->post($url, [
          'headers' => [
            'Authorization' => 'Bearer ' . $this->bearerToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'json' => [
            'contract_id' => $contractId,
            'buyer_phone' => $buyerPhone
          ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function uploadAct($contractId, $file)
    {
        $client = new Client();
        $url = $this->baseUrl . 'contracts/upload-act';

        $multipart = new MultipartStream([
          [
            'name' => 'act',
            'contents' => fopen($file->path(), 'r'),
            'filename' => $file->getClientOriginalName(),
            'headers'  => ['Content-Type' => $file->getMimeType()]
          ],
          [
            'name' => 'id',
            'contents' => $contractId
          ]
        ]);

        $request = new Psr7Request('POST', $url, [
          'Authorization' => 'Bearer ' . $this->bearerToken,
          'Content-Type' => 'multipart/form-data; boundary=' . $multipart->getBoundary()
        ], $multipart);

        try {
            $response = $client->send($request);
        } catch (GuzzleException $e) {
            //
        }

        return json_decode($response->getBody(), true);
    }

    public function uploadClientPhoto($clientContractId, $file)
    {
        $client = new Client();
        $url = $this->baseUrl . 'contracts/upload-client-photo';

        $multipart = new MultipartStream([
          [
            'name' => 'client_photo',
            'contents' => fopen($file->path(), 'r'),
            'filename' => $file->getClientOriginalName(),
            'headers'  => ['Content-Type' => $file->getMimeType()]
          ],
          [
            'name' => 'id',
            'contents' => $clientContractId
          ]
        ]);

        $request = new Psr7Request('POST', $url, [
          'Authorization' => 'Bearer ' . $this->bearerToken,
          'Content-Type' => 'multipart/form-data; boundary=' . $multipart->getBoundary()
        ], $multipart);

        try {
            $response = $client->send($request);
        } catch (GuzzleException $e) {
            //
        }

        return json_decode($response->getBody(), true);
    }
}