<?php

namespace App\Library\Payments\Paycom_old;

use Schema;
use App;
use App\Library\Payments\ErrorException;
use App\Payment;
use Illuminate\Http\Request;

class Controller extends App\Library\Payments\BaseController
{

    protected static $system_name = 'paycom';
    protected $request_id = null;
    protected $parameters = [];
    private static $transaction_states = [
        'pending' => ['code' => 1, 'desc' => 'Транзакция успешно создана, ожидание подтверждения (начальное состояние 0)',],
        'commited' => ['code' => 2, 'desc' => 'Транзакция успешно завершена (начальное состояние 1)',],
        'canceled' => ['code' => -1, 'desc' => 'Транзакция отменена (начальное состояние 1)',],
        'canceled_after_commit' => ['code' => -2, 'desc' => 'Транзакция отменена после завершения (начальное состояние 2)',],
    ];
    public static $cancel_trunsaction_resons =
        [
            '-' => ['code' => null, 'desc' => 'Не отменено'],
            'receiver_not_found' => ['code' => 1, 'desc' => 'Один или несколько получателей не найдены или не активны в Paycom'],
            'debit_processing_error' => ['code' => 2, 'desc' => 'Ошибка при выполнении дебетовой операции в процессингом центре.'],
            'transaction_error' => ['code' => 3, 'desc' => 'Ошибка выполнения транзакции'],
            'by_timeout' => ['code' => 4, 'desc' => 'Отменена по таймауту'],
            'cache_back' => ['code' => 5, 'desc' => 'Возврат денег'],
            'unknown' => ['code' => 10, 'desc' => 'Неизвестная ошибка'],
        ];
    public static $cancel_trunsaction_resons_by_code =
        [
            null => '-',
            1 => 'receiver_not_found',
            2 => 'debit_processing_error',
            3 => 'transaction_error',
            4 => 'by_timeout',
            5 => 'cache_back',
            10 => 'unknown',
        ];
    protected static $def_config = [
        'username' => null,
        'password' => null,
        'merchant_id' => null,
        'system_account_id_name' => null,
    ];
    private static $error_messages =
        [
            -31001 => 'Неверная сумма',
            -31003 => 'Транзакция не найдена',
            -31007 => 'Невозможно отменить транзакцию, заказ выполнен. Товар или услуга предоставлена Потребителю в полном объеме',
            -31008 => 'Невозможно выполнить данную операцию',
            -31051 => 'Заказ не найден',
            -31052 => 'Нет данных',
            -31053 => 'Недоступно для оплаты. Проверьте статус заказа',
            -31054 => 'Пользователь с имеющимся заказом не найден',
            -31055 => 'Заказ уже оплачен',
            -31056 => 'Состояние счета в ожидании оплаты',
            -31057 => 'Данный тип недоступен для оплаты',
            -31058 => 'Заказ был прежде отменен',
            -32400 => 'Системная ошибка',
            -32300 => 'Эта ошибка возникает если запрос передается не с помощью HTTP POST',
            -32700 => 'Ошибка Парсинга JSON. Запрос является не валидным JSON объектом',
            -32600 => 'Передан неправильный JSON-RPC объект',
            -32601 => 'Запрашиваемый метод не найден. Поле data содержит запрашиваемый метод',
            -32504 => 'Недостаточно привилегий для выполнения метода',
            -31099 => ' обязательно.',
        ];
    private static $request_params =
        [
            'CheckPerformTransaction' => [
                'amount',
                'account',
            ],
            'CreateTransaction' => [
                'id',
                'time',
                'amount',
                'account',
            ],
            'PerformTransaction' => [
                'id',
            ],
            'CancelTransaction' => [
                'id',
                'reason',
            ],
            'CheckTransaction' => [
                'id'
            ],
            'GetStatement' => [
                'from',
                'to'
            ],
            'ChangePassword' =>
                [
                    'password'
                ],
        ];

    // CASTOMISATIONS
    public function getAccountId()
    {
        $account_id_name = self::config('system_account_id_name', true);
        if (!isset($this->parameters['account'][$account_id_name]))
            $this->wrongParametrs();

        return $this->parameters['account'][$account_id_name];
    }

    public function getAmount()
    {
        return round($this->parameters['amount'] / 100);
    }

    public function getReceivers($transaction)
    {
        return null;
    }

    public function changePassword()
    {
        $this->error(-32601);
    }

    //
    public function response($result, $error = null)
    {
        if (isset($result['transaction']))
            $result['transaction'] = (string)$result['transaction'];

        if (isset($result['create_time']))
            $result['create_time'] = (int)$result['create_time'];

        if (isset($result['perform_time']))
            $result['perform_time'] = (int)$result['perform_time'];

        if (isset($result['cancel_time']))
            $result['cancel_time'] = (int)$result['cancel_time'];

        if (isset($result['amount']))
            $result['amount'] = (int)$result['amount'];

        $response = [
            'error' => $error,
            'result' => $result
        ];

        if ($this->request_id)
            $response['id'] = $this->request_id;

        return $response;
    }

    public function wrongParametrs()
    {
        $this->error(-32600);
    }

    public function userDoesNotExists()
    {
        $this->error(-31054);
    }

    public function orderDoesNotExists()
    {
        $this->error(-31051);
    }

    public function productDoesNotExists()
    {
        $this->orderDoesNotExists();
    }

    public function serviceDoesNotExists()
    {
        $this->orderDoesNotExists();
    }

    public function numberDoesNotExists()
    {
        $this->orderDoesNotExists();
    }

    public function orderAlreadyPayed()
    {
        $this->error(-31055);
    }

    public function cardAlreadyRegistred()
    {
        $this->orderAlreadyPayed();
    }

    public function serviceUnvailable()
    {
        $this->error(-31008);
    }

    public function amountIsInvalid()
    {
        $this->error(-31001);
    }

    public function error($code, $message = null)
    {
        throw new ErrorException($message, $code);
    }

    public function callbackRoute(Request $request)
    {
        $response = null;
        try {
//            $this->checkAuth();
            if ($request->getMethod() != 'POST')
                $this->error(-32300);

            $json = $request->json()->all();
            if (empty($json)){
                $this->error(-32700);
            }
            if (!empty($json['method']) && isset(self::$request_params[$json['method']])) {
                $this->parameters = isset($json['params']) ? $json['params'] : [];
                if (!empty($json['id'])){
                    $this->request_id = $json['id'];
                }
                if (self::$request_params[$json['method']]){
                    foreach (self::$request_params[$json['method']] as $f_name) {
                        if (!isset($this->parameters[$f_name])) {
                            $this->error(-31099, $f_name . self::$error_messages[-31099]);
                        }
                    }
                }

                if (isset($this->parameters['account'])) {
                    $this->checkPayment();
                }

                if (isset($this->parameters['amount'])) {
                    $this->checkAmount();
                }

                $method_name = 'action' . $json['method'];

                $response = $this->$method_name();
            } else{
                $this->error(-32600);
            }
        } catch (ErrorException $e) {
            $mess = $e->getMessage();
            $code = $e->getCode();
            if ($mess == null) {
                if (!isset(self::$error_messages[$code]))
                    $code = -32400;
                $mess = self::$error_messages[$code];
            }
            // TODO: translate
            if (!is_array($mess))
                $mess = [
                    'ru' => $mess,
                    'uz' => $mess,
                    'en' => $mess
                ];
            $data = null;
            if (isset($this->parameters['account']))
                $data = self::config('system_account_id_name', true);
            $response = $this->response(null, [
                'code' => $code,
                'message' => $mess,
                'data' => $data,
            ]);
            if (self::config('debug', true)) {
                $response['trace'] = $e->getTrace();
                $response['json'] = $request->json()->all();
                $response['parameters'] = $this->parameters;
            }
            \Log::info($e->getTraceAsString());
        } catch (Exception $e) {
            $mess = self::config('debug', true)
                ? $e->getTrace()
                : self::$error_messages[-32400];

            $response = $this->response(null, [
                'code' => -32400,
                'message' => $mess,
            ]);
        }
        $this->storeLog($request->all(), $response);

        return response()->json($response);
    }

    public function getPassword()
    {
        $last_password = \DB::table(self::config('table_prefix', true) . 'paycom_passwords')
            ->select('password')
            ->latest('id')
            ->first();

        if (!$last_password) {
            return self::config('password');
        }

        return $last_password->password;
    }

    public function checkAuth()
    {
        $user = \Request::server('PHP_AUTH_USER');
        $pass = \Request::server('PHP_AUTH_PW');
        //\Log::info('auth',[$user,$pass]);
        $paycom_user = '';
        $paycom_key = '';

        if (!$user || $user != $paycom_user || $pass != $paycom_key) {
            // $title='Access denied.';
            // if($user)
            // 	$title.=" user";
            // header('WWW-Authenticate: Basic realm="'.$title.'"');
            // header('HTTP/1.0 401 Unauthorized');
            // echo 'Доступ закрыт';
            $this->error(-32504);
        }
    }

    public function checkCreatedTimeout($transaction)
    {
        $current_time = round(microtime(true) * 1000);
        $timeout = $current_time - $transaction->paycom_system_time_created;
        //\Log::info('timeout',[$timeout,$current_time, $transaction->paycom_system_time_created]);;
        // 720000
        // 12*60*60*1000
        if ($timeout > 43200000) {
            $transaction->update([
                'state' => 'canceled',
                'paycom_cancel_reason' => 'by_timeout',
                'paycom_time_canceled' => $current_time,
                'updated_at' => new \DateTime(),
            ]);
            $this->error(-31008, 'Невозможно выполнить данную операцию. Отмена по таймауту');
        }
    }

    public function actionCheckPerformTransaction()
    {
        return $this->response([
            'allow' => true,
        ]);
    }

    public function actionCreateTransaction()
    {
        $transaction = Payment::where('txn_code', $this->parameters['id'])
            ->where('payment_method', self::$system_name)->first();
        $data = null;
        if (!$transaction) {
            $paycom_time_created = round(microtime(true) * 1000);
            $state = 'pending';

            $transaction = Payment::create([
                'txn_code' => $this->parameters['id'],
                'paycom_system_time_created' => $this->parameters['time'],
                'amount' => $this->getAmount(),
                'seller_id' => $this->getAccountId(),
                'payment_details' => json_encode($this->parameters['account']),
                'paycom_time_created' => $paycom_time_created,
                'state' => $state,
                'payment_method' => self::$system_name,
                'created_at' => new \DateTime(),
            ]);

            $this->onTransactionCreated($transaction);

            $data = [
                'create_time' => $paycom_time_created,
                'transaction' => $transaction->id,
                'state' => self::$transaction_states[$state]['code']
            ];
        } else {
            if ($transaction->state != 'pending')
                $this->error(-31056);

            $this->checkCreatedTimeout($transaction);

            $data = [
                'create_time' => $transaction->paycom_time_created,
                'transaction' => $transaction->id,
                'state' => self::$transaction_states[$transaction->state]['code'],
            ];
        }

        $receivers = $this->getReceivers($transaction);
        if ($receivers != null)
            $data['receivers'] = $receivers;

        return $this->response($data);
    }

    public function actionPerformTransaction()
    {

        $transaction = Payment::where('txn_code', $this->parameters['id'])
            ->where('payment_method', self::$system_name)->first();
        if (!$transaction)
            $this->error(-31003);
        if ($transaction->state == 'pending') {
            $this->checkCreatedTimeout($transaction);

            $perform_time = round(microtime(true) * 1000);
            $state = 'commited';
            $transaction->update([
                'state' => $state,
                'paycom_time_preform' => $perform_time,
                'updated_at' => new \DateTime(),
            ]);
            $this->onTransactionCommited($transaction);

            return $this->response([
                'transaction' => $transaction->id,
                'perform_time' => $perform_time,
                'state' => self::$transaction_states[$state]['code'],
            ]);
        } elseif ($transaction->state == 'commited') {
            return $this->response([
                'perform_time' => $transaction->paycom_time_preform,
                'transaction' => $transaction->id,
                'state' => self::$transaction_states[$transaction->state]['code'],
            ]);
        }

        $this->error(-31008);
    }

    public function actionCancelTransaction()
    {

        $transaction = Payment::where('txn_code', $this->parameters['id'])
            ->where('payment_method', self::$system_name)->first();

        if (!$transaction)
            $this->error(-31003);
        $response = null;
        if ($transaction->state == 'pending' || $transaction->state == 'commited') {
            if ($transaction->state == 'commited') {
                if (!$this->isCancalableTransaction($transaction)) {
                    $this->error(-31007);
                }
                $state = 'canceled_after_commit';
            } else {
                $state = 'canceled';
            }

            $cancel_time = round(microtime(true) * 1000);
            $transaction->update([
                'state' => $state,
                'paycom_cancel_reason' => self::$cancel_trunsaction_resons_by_code[$this->parameters['reason']],
                'paycom_time_canceled' => $cancel_time,
                'updated_at' => new \DateTime(),
            ]);
            $this->onTransactionCanceled($transaction);
            $response = [
                'transaction' => $transaction->id,
                'cancel_time' => $cancel_time,
                'state' => self::$transaction_states[$state]['code'],

            ];
        } else {
            $response = [
                'transaction' => $transaction->id,
                'cancel_time' => $transaction->paycom_time_canceled,
                'state' => self::$transaction_states[$transaction->state]['code'],
            ];
        }

        return $this->response($response);
    }

    public function actionCheckTransaction()
    {
        $transaction = Payment::where('txn_code', $this->parameters['id'])
            ->where('payment_method', self::$system_name)
            ->first();

        if (!$transaction)
            $this->error(-31003);

        return $this->response([
            'create_time' => $transaction->paycom_time_created,
            'perform_time' => $transaction->paycom_time_preform,
            'cancel_time' => $transaction->paycom_time_canceled,
            'transaction' => $transaction->id,
            'state' => self::$transaction_states[$transaction->state]['code'],
            'reason' => self::$cancel_trunsaction_resons[$transaction->paycom_cancel_reason]['code']
        ]);
    }

    public function actionGetStatement()
    {
        $transactions = Payment::select([
            'txn_code as id',
            'id as transaction',
            'paycom_system_time_created as time',
            'amount as amount',
            'payment_details as parameters',
            'paycom_time_created as create_time',
            'paycom_time_preform as perform_time',
            'paycom_time_canceled as cancel_time',
            'state',
            'paycom_cancel_reason as reason'

        ])
            ->orderBy('paycom_time_created')
            ->whereBetween('paycom_time_created', [$this->parameters['from'], $this->parameters['to']])
            ->get();

        if ($transactions) {
            $el = $this;
            $transactions = collect($transactions)->transform(function ($trans, $key) use ($el) {
                $trans->time = $trans->time;
                $trans->account = json_decode($trans->parameters, true);
                unset($trans->parameters);
                $trans->create_time = (int)$trans->create_time;
                $trans->perform_time = (int)$trans->perform_time;
                $trans->cancel_time = (int)$trans->cancel_time;
                $trans->state = self::$transaction_states[$trans->state]['code'];
                $trans->reason = self::$cancel_trunsaction_resons[$trans->reason]['code'];
                $receivers = $el->getReceivers($trans);
                if ($receivers != null)
                    $trans->receivers = $receivers;

                return $trans;
            });
        }

        return $this->response([
            'transactions' => $transactions,
        ]);
    }

    public function actionChangePassword()
    {
        $path = config_path('payments.php');

        if ($this->getPassword() == $this->parameters['password']) {
            $this->error(-32504);
        }

        $create_password = \DB::table(self::config('table_prefix', true) . 'paycom_passwords')
            ->insert([
                'password' => $this->parameters['password'],
                'created_at' => new \DateTime()
            ]);

        if (!$create_password) {
            $this->error(-32400);
        }

        return $this->response([
            "success" => true
        ]);
    }

    public function test()
    {
        $url = "http://checkout.test.paycom.uz";

        $headers = [
            'Content-Type: application/json; charset=UTF-8',
        ];
        $array =
            [
                "merchant" => "56a07es858cb0d102137f29cf",
                "amount" => "10000",
                "account" => [
                    "request" => "1",
                ],
                "lang" => "ru"
            ];
        $ch = curl_init();
        curl_setopt_array($ch,
            [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => http_build_query($array),
                CURLOPT_SSL_VERIFYPEER => false
            ]);

        $response = curl_exec($ch);
    }
}