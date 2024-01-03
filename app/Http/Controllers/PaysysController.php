<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Session;

class PaysysController extends Controller
{

    protected $vendor_id;
    protected $secret_key;

    public function __construct()
    {
        $this->vendor_id = (int) env('PAYSYS_VENDOR_ID');
        $this->secret_key = env('PAYSYS_SECRET_KEY');
    }

    public function getCheckout()
    {
        if (get_setting('paysys_sandbox') == 1) {
            $url = 'https://agr.uz/sandbox';
        } else {
            $url = 'https://agr.uz/pay';
        }

        $order = Order::findOrFail(Session::get('order_id'));
        $amount = $order->grand_total;

        // $order->code = date('Ymd-His').rand(10,99);
        $params = [
            'VENDOR_ID' => $this->vendor_id,
            'MERCHANT_TRANS_ID' => $order->id,
            'MERCHANT_TRANS_AMOUNT' => $amount,
            'MERCHANT_CURRENCY' => 'sum', // \App\Currency::findOrFail(get_setting('system_default_currency'))->code,
            'MERCHANT_TRANS_NOTE' => 'Azbo payment store',
            // 'MERCHANT_TRANS_DATA' => 'eyJrZXkxIjoidmFsdWUxIiwia2V5MiI6InZhbHVlMiJ9',
            'MERCHANT_TRANS_RETURN_URL' => route('paysys.back', ['order_id' => encrypt($order->id)]),
            'SIGN_TIME' => now()->timestamp,
        ];

        $params['SIGN_STRING'] = md5($this->secret_key . $params['VENDOR_ID'] .
            $params['MERCHANT_TRANS_ID'] . $params['MERCHANT_TRANS_AMOUNT'] .
            $params['MERCHANT_CURRENCY'] . $params['SIGN_TIME']);

        return view('frontend.paysys.index', compact('url', 'params'));
    }


    /**
     * MERCHANT_TRANS_ID
     * SIGN_TIME
     * SIGN_STRING
     */
    public function info(Request $request)
    {
        \Log::info('info: ' . json_encode($request->all()));
        if(!$request->has('MERCHANT_TRANS_ID') && !$request->has('SIGN_TIME') ) {
            return response()->json([
                'ERROR' => -8, 
                'ERROR_NOTE' => 'Error in request'
            ]);
        }

        if(!$request->has('MERCHANT_TRANS_ID') || !$request->has('SIGN_TIME') || !$request->has('SIGN_STRING')) {
            return response()->json([
                'ERROR' => -3, 
                'ERROR_NOTE' => 'Not enough parameters'
            ]);
        }


        $sign_string = md5(
            $this->secret_key .
            $request->get('MERCHANT_TRANS_ID') .
            $request->get('SIGN_TIME')
        );

        if ($sign_string != $request->get('SIGN_STRING')) {
            \Log::info('Notify: does not much !' . json_encode($request->all()));
            return response()->json([
                'ERROR' => -1,
                'ERROR_NOTE' => 'SIGN CHECK FAILED!',
            ]);
        }

        $order_id = $request->get('MERCHANT_TRANS_ID');
        $order = Order::find($order_id);
        if(!$order) {
            return response()->json([
                'ERROR' => -5, 
                'ERROR_NOTE' => 'The order does not exist'
            ]);
        }



        return response()->json([
            'ERROR' => 0,
            'ERROR_NOTE' => 'Success',
            'PARAMETERS' => [],
        ]);
    }


    /**
     * This method is called when need to confirm a payment
     *
     * ENVIROMENT
     * VENDOR_ID
     * PAYMENT_ID
     * PAYMENT_NAME
     * AGR_TRANS_ID
     * MERCHANT_TRANS_ID
     * MERCHANT_TRANS_AMOUNT
     * SIGN_TIME
     * SIGN_STRING
     */
    public function pay(Request $request)
    {

        \Log::info('pay: ' . json_encode($request->all()));
        if (count($request->all()) == 0) {
            return response()->json([
                'ERROR' => -8,
                'ERROR_NOTE' => 'Error in request'
            ]);
        }
       
        
        if (count($request->all()) != 9) {
            return response()->json([
                'ERROR' => -3,
                'ERROR_NOTE' => 'Not enough parameters'
            ]);
        }
      
        $sign_string = md5(
            $this->secret_key .
            $request->get('AGR_TRANS_ID') .
            $request->get('VENDOR_ID') .
            $request->get('PAYMENT_ID') .
            $request->get('PAYMENT_NAME') .
            $request->get('MERCHANT_TRANS_ID') .
            $request->get('MERCHANT_TRANS_AMOUNT') .
            $request->get('ENVIRONMENT') .
            $request->get('SIGN_TIME')
        );

        if ($sign_string != $request->get('SIGN_STRING')) {

            return response()->json([
                'ERROR' => -1,
                'ERROR_NOTE' => 'SIGN CHECK FAILED',
            ]);
        }

        $order = Order::find($request->get('MERCHANT_TRANS_ID'));

        if ($order && $order->payment_status == 2){
            return response()->json([
                'ERROR' => -4,
                'ERROR_NOTE' => 'Already paid'
            ]);
        }

        if(!$order) {
            return response()->json([
                'ERROR' => -5,
                'ERROR_NOTE' => 'The order does not exist'
            ]);
        }

        if($order->grand_total != $request->get('MERCHANT_TRANS_AMOUNT')) {
            return response()->json([
                'ERROR' => -2,
                'ERROR_NOTE' => 'Incorrect parameter amount'
            ]);
        }
        $order->payment_details = json_encode($request->all());
        $order->save();

        $params = ['ERROR' => 0, 'ERROR_NOTE' => 'Success', 'VENDOR_TRANS_ID' => $order->id];
        return response()->json($params, 200);
    }

    /**
     * AGR_TRANS_ID
     * VENDOR_TRANS_ID
     * STATUS
     * SIGN_TIME
     * SIGN_STRING
     */
    public function notify(Request $request)
    {
        \Log::info('notify: ' . json_encode($request->all()));

        if(count($request->all()) == 0) {
            return response()->json([
                'ERROR' => -8, 
                'ERROR_NOTE' => 'Error in request' 
            ]);
        }


        if(count($request->all()) != 5) {
            return response()->json([
                'ERROR' => -3, 
                'ERROR_NOTE' => 'Not enough parameters' 
            ]);
        }

        $sign_string = md5(
            $this->secret_key .
            $request->get('AGR_TRANS_ID') .
            $request->get('VENDOR_TRANS_ID') .
            $request->get('STATUS') .
            $request->get('SIGN_TIME')
        );

        if ($sign_string != $request->get('SIGN_STRING')) {
            return response()->json([
                'ERROR' => -1,
                'ERROR_NOTE' => 'SIGN CHECK FAILED',
            ]);
        }
        $order_id = $request->get('VENDOR_TRANS_ID');
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'ERROR' => -6,
                'ERROR_NOTE' => 'The transaction does not exist',
            ]);
        }


        $order->payment_status = $request->get('STATUS');
        $order->save();

        return response()->json([
            'ERROR' => 0,
            'ERROR_NOTE' => 'Success',
        ]);
    }

     /**
     * AGR_TRANS_ID
     * VENDOR_TRANS_ID
     * SIGN_TIME
     * SIGN_STRING
     */
    public function cancel(Request $request)
    {
        \Log::info('cancel: ' . json_encode($request->all()));
        if(count($request->all()) == 0) {
            return response()->json([
                'ERROR' => -8, 
                'ERROR_NOTE' => 'Error in request' 
            ]);
        }


        if(count($request->all()) != 4) {
            return response()->json([
                'ERROR' => -3, 
                'ERROR_NOTE' => 'Not enough parameters' 
            ]);
        }

        $sign_string = md5(
            $this->secret_key .
            $request->get('AGR_TRANS_ID') .
            $request->get('VENDOR_TRANS_ID') .
            $request->get('SIGN_TIME')
        );

        if ($sign_string != $request->get('SIGN_STRING')) {
            return response()->json([
                'ERROR' => -1,
                'ERROR_NOTE' => 'SIGN CHECK FAILED',
            ]);
        }

        $order_id = $request->get('VENDOR_TRANS_ID');
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'ERROR' => -6,
                'ERROR_NOTE' => 'The transaction does not exist',
            ]);
        }

        $order->payment_status = 'canceled';
        $order->save();

        return response()->json([
            "ERROR" => 0,
            "ERROR_NOTE" => "Success",
        ]);

    }


    public function back($order_id)
    {
        
        $order = Order::find(decrypt($order_id));

        $is_paid = false;

        if ($order->payment_status == 'paid') {
            $is_paid = true;

        } else { // check if payment done

            $order_details = json_decode($order->payment_details);

           
            $res = $this->check_payment($order_details->AGR_TRANS_ID, $order_details->PAYMENT_ID);


           
            
            if ($res->ERROR == 0) {
                $is_paid = true;
                $order->payment_status = 'paid';
                $order->save(); 
            }
        }

        if ($is_paid) {

            Session::forget('cart');
            Session::forget('owner_id');
            Session::forget('payment_type');
            Session::forget('delivery_info');
            Session::forget('coupon_id');
            Session::forget('coupon_discount');

            flash(translate('Payment completed'))->success();
            return redirect(route('order_confirmed'));
        } else {
            flash('Payment incompleted')->error();
            return redirect('/checkout');
        }
    }

    
    /**
     * With this request, PaySys verifies payments on a monthly basis.
     * AGR_TRANS_ID
     * VENDOR_ID
     * PAYMENT_ID
     * SIGN_TIME
     * SIGN_STRING
     */
    public function statement(Request $request)
    {
        \Log::info('PaysysController@statement: ' . json_encode($request->all()));

        if(count($request->all()) == 0) {
            return response()->json([
                'ERROR' => -8, 
                'ERROR_NOTE' => 'Error in request' 
            ]);
        }


        if(count($request->all()) != 4) {
            return response()->json([
                'ERROR' => -3, 
                'ERROR_NOTE' => 'Not enough parameters' 
            ]);
        }

        $sign_string = md5(
            $this->secret_key .
            $request->get('FROM') .
            $request->get('TO') .
            $request->get('SIGN_TIME')
        );

        if ($sign_string != $request->get('SIGN_STRING')) {
            return response()->json([
                'ERROR' => -1, 
                'ERROR_NOTE' => 'SIGN CHECK FAILED!' 
            ]);
        }


        return response()->json([
            'ERROR' => 0, 
            'ERROR_NOTE' => 'Success',
            'TRANSACTIONS' => [] 
        ]);
        
    }
   
    



    public function check_payment($AGR_TRANS_ID, $PAYMENT_ID)
    {

        $params = [
            'AGR_TRANS_ID' => $AGR_TRANS_ID,
            'VENDOR_ID' => $this->vendor_id,
            'PAYMENT_ID' => $PAYMENT_ID,
            'SIGN_TIME' => now()->timestamp,
        ];

        
        $params['SIGN_STRING'] = md5(
            $this->secret_key .
            $params['AGR_TRANS_ID'] .
            $params['VENDOR_ID'] .
            $params['PAYMENT_ID'] .
            $params['SIGN_TIME']
        );
        
       
        
        $headers = [
            'Accept:application/json',
            'Content-Type: application/json',
        ];

        $url = 'https://agr.uz/pay_api/payment_status';

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params)); /** send params as json  */
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response);
    }
    
}
