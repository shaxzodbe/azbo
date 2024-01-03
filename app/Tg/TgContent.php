<?php


namespace App\Tg;


use App\Models\Custom\OneClickBuy;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TgContent
{
    const url = 'https://api.telegram.org/bot';
    //const REPORT_TELEGRAM_ID = '5813936690'; // Nusrat
    //const REPORT_TELEGRAM_ID = "24097956"; // Maqsud aka
    const REPORT_TELEGRAM_ID = "-1001628804758";
    private $bot1 = '5841257804:AAEgH13lsjtq-NyXJeU1OVkWUj_QV5BPgjc';

    public function suspendido($chat_id, $foo)
    {

        $data = [
            'id' => $foo->id,
            'name' => $foo->product->name ?? $foo->name,
            'fio' => $foo->name ?? '',
            'email' => $foo->address ?? '',
            'photo' => uploaded_asset(explode(',', $foo->product->photos)[0]) ?? '',
//            'photo'=>'https://azbo.uz/public/uploads/all/ZohJ4tmcxs0KzDO3czukeDuuHOOOwvNGB4QLKHmr.jpg',
            'price' => $foo->product->unit_price ?? $foo->unit_price,
        ];

        $reply_markup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Rad qilish',
                        'callback_data' => '1|' . $foo->id
                    ],
                    [
                        'text' => 'Qabul qilish',
                        'callback_data' => '2|' . $foo->id
                    ],
                ]
            ]
        ];

        $client = new \GuzzleHttp\Client();
        $url = self::url . $this->bot1 . '/sendMessage';

        $response = $client->request(
            'POST',
            $url,
            [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => (string)view('frontend.new_order', $data),
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode($reply_markup),
                ]
            ]
        );
        return $response;

    }

    public function suspendido_cart($chat_id, $foo,$bar,$item)
    {
        $data = [
            'id' => $bar['order_id'],
            'author_name'=>$bar['sublayerName'],
            'sublayer_phone'=>$bar['sublayerPhone'],
            'shipping_type'=>$bar['shipping_type'],
            'price'=>$item['price'],
            'quantity'=>$item['quantity'],
            'product_name' => $foo->name,
            'photo' => uploaded_asset(explode(',', $foo->photos)[0]),
//            'photo'=>'https://azbo.uz/public/uploads/all/ZohJ4tmcxs0KzDO3czukeDuuHOOOwvNGB4QLKHmr.jpg',
        ];

        $client = new \GuzzleHttp\Client();
        $url = self::url . $this->bot1 . '/sendMessage';

        $response = $client->request(
            'POST',
            $url,
            [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => (string)view('frontend.new_order_cart', $data),
                    'parse_mode' => 'html',
                ]
            ]
        );

        return $response;

    }

    public function editButton(Request $request)
    {
        $public = explode('|', $request->input('callback_query')['data'])[0];
        $id = explode('|', $request->input('callback_query')['data'])[1];
        $message_id = $request->input('callback_query')['message']['message_id'];
        $order = OneClickBuy::where('id', $id)->first();

        if ($public == 1) {
            $reply_markup = [
                'inline_keyboard' =>
                    [
                        [
                            [
                                'text' => '❌ Rad qilish',
                                'callback_data' => '1|' . $order->id,
                            ],
                            [
                                'text' => 'Qabul qilish',
                                'callback_data' => '2|' . $order->id,
                            ],
                        ]
                    ]
            ];
        } else {
            $reply_markup = [
                'inline_keyboard' =>
                    [
                        [
                            [
                                'text' => 'Raq qilish',
                                'callback_data' => '1|' . $order->id,
                            ],
                            [
                                'text' => '✅ Qabul qilish',
                                'callback_data' => '2|' . $order->id,
                            ],
                        ]
                    ]
            ];
        }

        $data = [
            'id' => $order->id,
            'name' => $order->product->name,
            'fio' => $order->name,
            'email' => $order->address,
            'photo' => uploaded_asset(explode(',', $order->product->photos)[0]),
            'price' => $order->product->unit_price,
        ];
        $order->status = $public;
        $order->update();

        $client = new \GuzzleHttp\Client();
        $url = self::url . $this->bot1 . '/editMessageText';

        return $client->request(
            'POST',
            $url,
            [
                'form_params' => [
                    'chat_id' => self::REPORT_TELEGRAM_ID,
                    'text' => (string)view('frontend.new_order', $data),
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode($reply_markup),
                    'message_id' => $message_id,]
            ]
        );
    }


}