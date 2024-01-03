<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Order;

trait PaymentsTrait
{
    public function onTransactionCommited(Payment $transaction)
    {
        $order = Order::find($transaction->seller_id);
        if ($order && $order->status == 'pending') {
            $order->status = 'paid';
            $order->save();
        }
    }

    public function onTransactionCanceled(Payment $transaction)
    {
    }
}