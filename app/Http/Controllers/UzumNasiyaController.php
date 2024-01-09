<?php

namespace App\Http\Controllers;

use App\Services\UzumNasiyaService;
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
        $response = $this->uzumNasiyaService->checkUserStatus($phone, $callbackUrl);

        return response()->json($response);
    }
}
