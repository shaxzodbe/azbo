<?php

namespace App\Http\Controllers;


use App\Library\Payments\Paycom\Application;
use Illuminate\Http\Request;

class PaymeController
{
    use PaymentsTrait;

    private $paycomApplication;

    public function __construct()
    {
        $this->paycomApplication = new Application(config('paycom.config'));
    }

    public function run(Request $request)
    {
        $this->paycomApplication->run();
    }
}