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
        $response = $this->uzumNasiyaService->checkUserStatus($phone);
        return response()->json($response);
    }
}
