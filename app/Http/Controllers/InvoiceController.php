<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Session;
use PDF;
use Auth;

class InvoiceController extends Controller
{
    //download invoice
    public function invoice_download($id)
    {
        if(Session::has('currency_code')){
            $currency_code = Session::get('currency_code');
        }
        else{
            $currency_code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        }

        if($currency_code == 'BDT'){
            $font_family = "'Hind Siliguri','sans-serif'";
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }elseif($currency_code == 'AMD'){
            $font_family = "'arnamu','sans-serif'";
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }elseif($currency_code == 'ILS'){
            $font_family = "'Varela Round','sans-serif'";
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        }elseif($currency_code == 'AED'){
            $font_family = "'XBRiyaz','sans-serif'";
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        }else{
            $font_family = "'Roboto','sans-serif'";
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        $order = Order::findOrFail($id);
        return PDF::loadView('backend.invoices.invoice',[
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->download('order-'.$order->code.'.pdf');
    }
}
