<?php

namespace App\Http\Controllers;

use App\Library\Payments\Paycom\Format;
use App\Order;
use App\PaycomTransaction;
use Illuminate\Http\Request;
use Session;

class PaycomController extends Controller
{

    protected $merchant_id = "60eea64e8382d4b66c42611f";
    protected $vendor_id;

    protected $test_key = "";

    public function __construct()
    {
        $this->vendor_id = "UeVeORb1UzSn6Mg1&cYPGO4%RnUzfu%%Rhsc";
    }

    public function getCheckout()
    {
        $vendor_id = $this->vendor_id; // env('PAYCOM_VENDOR_ID');

        $array = [];

        if (get_setting('paycom_sandbox') == 1) {
            $url = 'https://test.paycom.uz/';
        } else {
            $url = 'https://checkout.paycom.uz/';
        }

        $format = new Format();
        $order = Order::findOrFail(Session::get('order_id'));

        $array = [
            'url' => $url,
            'amount' => $format->toCoins($order->grand_total),
            'order_id' => $order->id,
            'merchant_id' => config('paycom.config')['merchant_id']
        ];

        return $array;

//        $params = base64_encode('m=' . $vendor_id . ';' . 'ac.order_id=' . $order->id . ';a=' . $amount);
//
//        return redirect()->away($url . $params);
    }

    public function payment(Request $request)
    {
        switch ($request->get('method')) {
            case 'CheckPerformTransaction':
                return $this->CheckPerformTransaction($request);
                break;
            case 'CreateTransaction':
                return $this->CreateTransaction($request);
                break;
            case 'PerformTransaction':
                return $this->PerformTransaction($request);
                break;
            case 'CancelTransaction':
                return $this->CancelTransaction($request);
                break;
            case 'CheckTransaction':
                return $this->CheckTransaction($request);
                break;
            case 'GetStatement':
                return $this->GetStatement($request);
                break;
            case 'ChangePassword':
                return $this->ChangePassword($request);
                break;
            default:
                # code...
                break;
        }
    }

    public function CheckPerformTransaction(Request $request)
    {
        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }

        $params = $request->get('params');

        $order = Order::find($params['account']['order_id']);
        

        /** check order */
        if (!$order) {
            return [
                "error" => [
                    "code" => -31099,
                    "message" => [
                        "en" => "Order does not exist",
                        "ru" => "Order does not exist",
                        "uz" => "Order does not exist",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        /** check amount  */

        $amount = $params['amount'];

        if ($order->grand_total != $amount) {
            return [
                "error" => [
                    "code" => -31001,
                    "message" => [
                        "en" => "Invalid amount",
                        "ru" => "Invalid amount",
                        "uz" => "Invalid amount",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        if (!$order->paycom_transaction) {
            return [
                "error" => [
                    "code" => -31099,
                    "message" => [
                        "en" => "Transaction does not exist.",
                        "ru" => "Transaction does not exist.",
                        "uz" => "Transaction does not exist."
                    ]
                ]
            ];
        }

        if ($order->payment_status == 'unpaid') {
            return [
                "result" => [
                    "allow" => true,
                ],
            ];
        } else {
            return [
                "result" => [
                    "allow" => false,
                ],
            ];
        }
    }

    public function CreateTransaction(Request $request)
    {

        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }

        $params = $request->get('params');

        $order = Order::find($params['account']['order_id']);

        /** check order */
        if (!$order) {
            return [
                "error" => [
                    "code" => -31099,
                    "message" => [
                        "en" => "Order does not exist",
                        "ru" => "Order does not exist",
                        "uz" => "Order does not exist",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        /** check amount  */

        $amount = $params['amount'];

        if ($order->grand_total != $amount) {
            return [
                "error" => [
                    "code" => -31001,
                    "message" => [
                        "en" => "Invalid amount",
                        "ru" => "Invalid amount",
                        "uz" => "Invalid amount",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        $order->payment_details = json_encode($params);
        $order->save();

        $transaction = PaycomTransaction::where(['order_id' => $order->id, 'state' => 1])->first();

        if (!$transaction) {

            $transaction = PaycomTransaction::create([
                'paycom_transaction_id' => $params['id'],
                'paycom_time' => $params['time'],
                'paycom_time_datetime' => now()->toDateTimeString(),
                'create_time' => now()->toDateTimeString(),
                'perform_time' => 0,
                'cancel_time' => 0,
                'state' => 1, // inital state
                'order_id' => $order->id,
            ]);

            return [
                "result" => [
                    "create_time" => strtotime($transaction->create_time) * 1000,
                    "transaction" => "$order->id",
                    "state" => 1,
                ],
            ];
        }

        if ($transaction->paycom_transaction_id != $params['id']) {
            return [
                "error" => [
                    "code" => -31099,
                    "message" => [
                        "en" => "There is a pending transaction",
                        "ru" => "There is a pending transaction",
                        "uz" => "There is a pending transaction",
                    ],
                ],
            ];
        }

        if ($transaction->state == 1) {
            return [
                "result" => [
                    "create_time" => strtotime($transaction->create_time) * 1000,
                    "transaction" => "$order->id",
                    "state" => 1,
                ],
            ];
        }

    }

    public function CheckTransaction(Request $request)
    {
        // dd(Order::find(61)->payment_details);
        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }

        $params = $request->get('params');

        // $order = Order::whereJsonContains('payment_details', ['id' => $params['id']])->first();

        $transaction = PaycomTransaction::where('paycom_transaction_id', $params['id'])->first();

        if (!$transaction) {
            return [
                "error" => [
                    "code" => -31003,
                    "message" => [
                        "en" => "Transaction not found",
                        "ru" => "Transaction not found",
                        "uz" => "Transaction not found",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        $order_id = $transaction->order->id;

        if ($transaction->state == 1) {
            return [
                "result" => [
                    "create_time" => strtotime($transaction->create_time) * 1000,
                    "perform_time" => 0,
                    "cancel_time" => 0,
                    "transaction" => "$order_id",
                    "state" => 1,
                    "reason" => null,
                ],
            ];
        }

        if ($transaction->state == 2) {
            return [
                "result" => [
                    "perform_time" => 0,
                    "cancel_time" => 0,
                    "reason" => null,
                ],
            ];
        }

        if ($transaction->state == -1) {

        }

        if ($transaction->state == -2) {

        }
    }

    public function PerformTransaction(Request $request)
    {

        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }

        $params = $request->get('params');

        $order = Order::whereJsonContains('payment_details', ['id' => $params['id']])->first();

        return response()->json([
            "result" => [
                "transaction" => $order->id,
                "perform_time" => $this->milli($order->created_at),
                "state" => 2,
            ],
        ]);

    }

    public function CancelTransaction(Request $request)
    {
        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }

        $params = $request->get('params');
        /** check if transaction exist */

        $transaction = PaycomTransaction::where('paycom_transaction_id', $params['id'])->first();

        if (!$transaction) {
            return [
                "error" => [
                    "code" => -31003,
                    "message" => [
                        "en" => "Transaction not found",
                        "ru" => "Transaction not found",
                        "uz" => "Transaction not found",

                    ],
                ],
                "id" => $request->get('id'),
            ];
        }

        if ($transaction->state == 2) {
            return [
                "error" => [
                    "code" => -31007,
                    "message" => [
                        "en" => "The order has been completed, Unable to cancel the transaction.",
                        "ru" => "The order has been completed, Unable to cancel the transaction.",
                        "uz" => "The order has been completed, Unable to cancel the transaction.",

                    ],
                ],
            ];
        }

        $transaction->state = -2;
        $transaction->cancel_time = now()->toDateTimeString();
        $transaction->save();

        return [
            "result" => [
                "transaction" => "$transaction->id",
                "cancel_time" => strtotime($transaction->cancel_time) * 1000,
            ],
        ];

    }

    public function GetStatement(Request $request)
    {
        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }
    }

    public function ChangePassword(Request $request)
    {
        $is_authorized = $this->is_authorized($request);
        if (!empty($is_authorized)) {
            return $is_authorized;
        }
    }

    /**
     *
     * round(microtime(true) * 1000)
     */

    public function milfli($date)
    {
        $t = $date->timestamp . str_pad($date->milli, 3, '0', STR_PAD_LEFT);

        return intval($t);
    }

    private function is_authorized($request)
    {
        $authorization = $request->header('Authorization');

        if (!$authorization || $authorization != "Basic " . base64_encode("Paycom:$this->vendor_id")) {

            return [
                "error" => [
                    "code" => -32504,
                    "message" => [
                        "en" => "Unauthorized !",
                        "ru" => "Unauthorized !",
                        "uz" => "Unauthorized !",
                    ],
                ],
                "id" => $request->get('id'),
            ];
        }
        return [];
    }

    private function check_amount($request)
    {

    }

    private function check_order($request)
    {

    }

}
