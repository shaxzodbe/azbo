<?php

namespace App\Library\Payments;

use App\Payment;
use App\Traits\GoogleAnalyticsTrait;
use DB;
use Config;

abstract class BaseController extends \Illuminate\Routing\Controller
{

    public $order;

    protected static $system_name = null;
    protected $parameters = [
        'amount' => null,
    ];
    protected $user = null;
    protected static $def_config_global = [
        'table_prefix' => '',
        'users_table' => 'users',
        'user_id' => 'id',
        'user_name' => 'name',
        'user_amount' => 'total_cost',
        'system_account_id_name' => 'user_id',
        'min_amount' => 1,
        'max_amount' => 999999999,
        'debug' => false,
    ];

    public static function config($f_name, $global = false)
    {
        $system_prefix = '';
        $def_config = self::$def_config_global;
        if (!$global) {
            $def_config = static::$def_config;
            $system_prefix = 'systems.' . static::$system_name . '.';
        }

        return Config::get('payments.' . $system_prefix . $f_name, $def_config[$f_name]);
    }

    public function storeLog($request, $response)
    {
        // \Log::info('Log',[$request,$response]);
//        return DB::table(self::config('table_prefix', true) . 'paymetnts_log')
//            ->insert(
//                [
//                    'system' => static::$system_name,
//                    'request' => json_encode($request),
//                    'response' => json_encode($response),
//                    'created_at' => date('Y-m-d H:i:s')
//                ]);
    }

    // CASTOMISATIONS
    public function onTransactionCreated(Payment $transaction)
    {

    }

    public function onTransactionCommited(Payment $transaction)
    {

    }

    public function onTransactionCanceled(Payment $transaction)
    {

    }

    public function isCancalableTransaction(Payment $transaction)
    {
        return true;
    }

    protected function checkPayment()
    {
        $this->user = DB::table(self::config('users_table', true))
            ->where(self::config('user_id', true), $this->getAccountId())
            ->first();
        if (!$this->user)
            $this->userDoesNotExists();
    }

    protected function getAmount()
    {
        return $this->parameters['amount'];
    }

    protected function checkAmount()
    {
        $min_amount = self::config('min_amount', true);
        $max_amount = self::config('max_amount', true);
        $amount = $this->getAmount();
        // \Log::info($amount);
        if ($min_amount != 0 && $amount < $min_amount) {
            $this->amountIsInvalid();
        } elseif ($max_amount != 0 && $amount > $max_amount) {
            $this->amountIsInvalid();
        } elseif ($this->user->{self::config('user_amount', true)} != $amount) {
            $this->amountIsInvalid();
        }
    }

    abstract public function getAccountId();

    public static function getRequestIp()
    {
        if (\Request::server('HTTP_CLIENT_IP'))
            $IP = \Request::server('HTTP_CLIENT_IP');
        elseif (\Request::server('HTTP_X_FORWARDED_FOR'))
            $IP = \Request::server('HTTP_X_FORWARDED_FOR');
        else
            $IP = \Request::server('REMOTE_ADDR');

        return $IP;
    }

    // CUSTOMISATION HELPER
    abstract public function wrongParametrs();

    abstract public function userDoesNotExists();

    abstract public function orderDoesNotExists();

    abstract public function productDoesNotExists();

    abstract public function serviceDoesNotExists();

    abstract public function numberDoesNotExists();

    abstract public function orderAlreadyPayed();

    abstract public function cardAlreadyRegistred();

    abstract public function serviceUnvailable();

    abstract public function amountIsInvalid();
}