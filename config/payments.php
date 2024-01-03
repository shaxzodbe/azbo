<?php

return [

	'table_prefix' => '',
	'users_table' => 'orders',
	'user_id' => 'id',
	'user_name' => 'user_id',
	'user_amount' => 'grand_total',
	'system_account_id_name' => 'order_id',
	'min_amount' => 500,
	'max_amount' => 999999999999,
	'debug' => env('PAYMENTS_DEBUG', false),
	'limit' => 50000000,
	'systems'=>
	[
	    'apelsin'=>[
            'secret_key'=>'FM4Ll1vO8Z6HAw',
            'merchant_id'=>'390faf8ea5ed4024a16f2d96fa8edf02',
            'merchant_service_id'=>'14140',
            'merchant_user_id'=>'13514',
            'max_amount' => 999999999999
        ],
        'click' => [
            'secret_key'=>'FM4Ll1vO8Z6HAw',
            'merchant_id'=>'9709',
            'merchant_service_id'=>'14140',
            'merchant_user_id'=>'13514',
            'max_amount' => 999999999999
        ],
        'paycom' => [
            'username'=>'Paycom',
            'password'=>'m5aV5Du19ADxf70m?RDmdj#MIq7uA#De4kYR',
            'merchant_id'=>'5d53b20759d0dba25945b022',
            'max_amount' => 999999999999
        ],

		'mbank' => [
			'merchant_id' => '205',
			'secret_key' => 'ehDYESYHIFOXrjpXGYsJTmXCcyUsUBaa',
			'max_amount' => 999999999999
		],
        'upay' => [
            'url' => 'https://pay.smst.uz/prePay.do',
            'serviceId' => 308,
            'apiVersion' => 1,
            'accessToken' => 'o92Kv9j5K1bjNtMGw0uuphFawpC4H8DC'
        ]
	],
    'instalment' =>
    [
        'click' => [
            'secret_key'  => 'asUHkCithOL8jG',
            'merchant_id' => '8813',
            'max_amount'  => 9999999,
            'merchant_user_id' => '11914',
            'merchant_service_id' => '13211'
        ],

        'paycom' => [
            'username' => 'Paycom',
            'id'        => '5bf5242404a37daf2abe5abf',
            'key'       => 'oJdVtNbU?XEXVD@jPdz?6GSFBhpMDhi0GqQ8',
            'test_key'  => '4?t2xAyFKm?prR6Z6xMR1feKgukwkS1Xq2&O',
//
//            'password' => '?jx2kns3iFSQsp24@fwAohIyyRvt0%tCtR5E',
//            'merchant_id' => '59ccca65c77924e964f1e07b',
//            'max_amount'  => 8000000
        ],

        'mbank' => [
            'merchant_id' => '205',
            'secret_key' => 'ehDYESYHIFOXrjpXGYsJTmXCcyUsUBaa',
            'max_amount' => 8000000
        ],
        'upay' => [
            'url' => 'https://pay.smst.uz/prePay.do',
            'serviceId' => 308,
            'apiVersion' => 1,
            'accessToken' => 'o92Kv9j5K1bjNtMGw0uuphFawpC4H8DC'
        ]
    ]
];