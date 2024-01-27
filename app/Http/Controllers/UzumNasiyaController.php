<?php

namespace App\Http\Controllers;

use App\Services\UzumNasiyaService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UzumNasiyaController extends Controller
{
    protected $uzumNasiyaService;

    public function __construct(UzumNasiyaService $uzumNasiyaService)
    {
        $this->uzumNasiyaService = $uzumNasiyaService;
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $callbackUrl = $request->input('callbackUrl');

        $response = $this->uzumNasiyaService->checkUserStatus($phone, $callbackUrl);

        return response()->json($response);
    }

    public function calculate(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $products = $request->input('products');

        $response = $this->uzumNasiyaService->calculate($userId, $products);

        return response()->json($response);
    }

    public function order(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $period = $request->input('period');
        $callback = $request->input('callback');
        $products = $request->input('products');

        $response = $this->uzumNasiyaService->createOrder($userId, $period, $callback, $products);

        return response()->json($response);
    }

    public function confirmContract(Request $request): JsonResponse
    {
        $contractId = $request->input('contract_id');

        $response = $this->uzumNasiyaService->confirmContract($contractId);

        return response()->json($response);
    }

    public function cancelContract(Request $request): JsonResponse
    {
        $contractId = $request->input('contract_id');
        $buyerPhone = $request->input('buyer_phone');

        $response = $this->uzumNasiyaService->cancelContract($contractId, $buyerPhone);

        return response()->json($response);
    }

    /**
     * @throws GuzzleException
     */
    public function uploadAct(Request $request): JsonResponse
    {
        $contractId = $request->input('id');
        $file = $request->file('act');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }

        $response = $this->uzumNasiyaService->uploadAct($contractId, $file);

        return response()->json($response);
    }

    public function uploadClientPhoto(Request $request): JsonResponse
    {
        $clientContractId = $request->input('id');
        $file = $request->file('client_photo');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }

        $response = $this->uzumNasiyaService->uploadClientPhoto($clientContractId, $file);

        return response()->json($response);
    }
}
