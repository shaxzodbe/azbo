<?php

namespace App\Http\Controllers;

use App\ProductUzumNasiya;
use App\Services\UzumNasiyaService;
use App\UzumNasiya;
use Exception;
use Illuminate\Http\Request;

class UzumNasiyaController extends Controller
{
    protected $uzumNasiyaService;

    public function __construct(UzumNasiyaService $uzumNasiyaService)
    {
        $this->uzumNasiyaService = $uzumNasiyaService;
    }

    public function checkStatus(Request $request)
    {
        $phone = $request->input('phone');
        $callbackUrl = $request->input('callback');

        try {
            $response = $this->uzumNasiyaService->checkUserStatus($phone, $callbackUrl);

            if (isset($response['status']) && $response['data']['status'] === ProductUzumNasiya::STATUS_VERIFIED) {
                $uzumNasiya = new UzumNasiya;
                $uzumNasiya->buyer_id = $response['data']['buyer_id'];
                $uzumNasiya->status = $response['data']['status'];
                $uzumNasiya->available_periods = $response['data']['available_periods'];
                $uzumNasiya->save();
            } else {
                //asdasd
            }

            return response()->json($response);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function calculate()
    {
        //
    }
}
