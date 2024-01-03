<?php


namespace App\Library\Payments\Paycom_old;


use App;
use App\Library\Payments\DTOLoader;
use App\Library\Payments\PaymentsParamsInterface;
use Config;

class PaymeDTO extends DTOLoader implements PaymentsParamsInterface
{
    public function toArray(): array
    {
        return [
            'merchant' => $this->paymentSystem->merchant_id,
            'account['.Config::get('payments.system_account_id_name').']' => $this->order->id,
            'amount' => $this->order->total_cost.'00',
            'lang' => App::getLocale(),
        ];
    }

}
