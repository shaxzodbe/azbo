@extends('frontend.layouts.app')

@section('content')
    <section class="py-4 gry-bg">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-8 mx-auto text-left">
                    <div class="shadow-sm bg-white p-4 rounded mb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header bg-soft-primary" id="headingTwo">
                                    <button class="btn  collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                            aria-expanded="true" aria-controls="collapseTwo">
                                        {{translate('Product')}}
                                    </button>
                                </div>
                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @php
                                                $total = 0;
                                                $subtotal = 0;
                                                $tax = 0;
                                                $shipping = 0;
                                            @endphp
                                            @foreach (Session::get('cart') as $key => $cartItem)
                                                @php

                                                    $product = \App\Product::find($cartItem['id']);
                                                    $total = $total + $cartItem['price'] * $cartItem['quantity'];
                                                    $subtotal += $cartItem['price']*$cartItem['quantity'];
                                                    $tax += $cartItem['tax']*$cartItem['quantity'];
                                                    $shipping += $cartItem['shipping'];
                                                    $product_name_with_choice = $product->getTranslation('name');
                                                    if ($cartItem['variant'] != null) {
                                                        $product_name_with_choice = $product->getTranslation('name') .'-'. get_variant_string($cartItem['variant']);
                                                    }

                                                    if($cartItem['bonus']) {
                                                        $product_name_with_choice .= ' (' . translate('bonus') . ')';
                                                    }
                                                @endphp

                                                <li class="list-group-item px-0 px-lg-3">
                                                    <div class="row gutters-5">
                                                        <div class="col-lg-8 d-flex">
                                                    <span class="mr-2 ml-0">
                                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                             class="img-fit size-60px rounded"
                                                             alt="{{ $product->getTranslation('name') }}">
                                                    </span>

                                                            <span
                                                                class="fs-14 opacity-60">{{ $product_name_with_choice }}</span>
                                                        </div>

                                                        <div class="col-lg-1 order-1 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Total') }}</span>
                                                            <span
                                                                class="fw-600 fs-16">{{$cartItem['quantity'] }}x </span>
                                                        </div>
                                                        <div class="col-lg-3 order-3 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Price') }}</span>
                                                            <span
                                                                class="fw-600 fs-16 text-primary">  {{ translate('Price').': '. single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity']) }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('checkout.action') }}" method="post" id="checkout-action-form">
                            @csrf
                            <div class="card shadow-0 border">

                                <div class="p-4">
                                    <h5 class="card-title mb-3">{{ __('intend.shipping_info')}}</h5>
                                    <br>
                                    <div class="row gutters-5">
                                        @if(session()->get('phone'))
                                        @foreach (\App\Address::where('phone',session()->get('phone'))->orderBy('id', 'desc')->take(1)->get() as $key => $address)
                                            <div class="col-md-12 mb-3">
                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                                           @if ($address->set_default)
                                                           checked
                                                           @endif required>
                                                    <span class="d-flex p-3" style="border-bottom: 1px solid #e2e5ec;">
                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                <span class="flex-grow-1 pl-3 text-left">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                             <div>
                                                                <span
                                                                    class="opacity-60">{{ translate('Country') }}:</span>
                                                                <span class="fw-600 ml-2">{{ $address->country }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="opacity-60">{{ translate('City') }}:</span>
                                                                <span class="fw-600 ml-2">{{ $address->city }}</span>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="opacity-60">{{ translate('Address') }}:</span>
                                                                <span class="fw-600 ml-2">{{ $address->address }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                                                            <div>
                                                                <span
                                                                    class="opacity-60">{{ translate('Postal Code') }}:</span>
                                                                <span
                                                                    class="fw-600 ml-2">{{ $address->postal_code }}</span>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="opacity-60">{{ translate('Phone') }}:</span>
                                                                <span class="fw-600 ml-2">{{ $address->phone }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </span>
                                                </label>
                                            </div>
                                        @endforeach
                                        @endif
                                        <input type="hidden" name="checkout_type" value="logged">
                                        <div class="col-md-12 mx-auto mb-3">
                                            <div
                                                class="border px-3 py-2 bg-soft-primary rounded c-pointer text-center bg-white h-100 d-flex flex-column justify-content-center"
                                                onclick="add_new_address()">

                                                <i class="las la-plus la-2x mb-1"></i> {{ translate('Add New Address') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-0 border">
                                <div class="p-4">
                                    <h5 class="card-title mb-3">2. {{__('intend.order_taker')}}</h5>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12 mb-2">
                                            <p class="mb-0">F.I.Sh</p>
                                            <div class="form-outline">
                                                <input type="text" name="sublayerName" placeholder="F.I.Sh "
                                                       class="form-control placeholder-active" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                            <p class="mb-0">{{translate('Phone')}}</p>
                                            <div class="form-outline">
                                                <input type="tel" id="sublayerPhone" name="sublayerPhone" placeholder="+998"
                                                       class="form-control placeholder-active active" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-0 border">
                                <div class="p-4">
                                    <h5 class="card-title mb-3">3.{{ translate('Select a payment option')}}</h5>
                                    <form action="{{ route('payment.checkout') }}" class="form-default" role="form"
                                          method="POST" id="checkout-form">
                                        @csrf
                                        <div class="card shadow-sm border-0 rounded">
                                            <div class="card-body text-center">
                                                <div class="row gutters-10">
                                                    @if(\App\BusinessSetting::where('type', 'paysys_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="paysys" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/paysys_logo.png')}}"
                                                             class="img-fluid w-md-120px mb-2">
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'paycom_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="paycom" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/payme.png')}}"
                                                             class="img-fluid mb-2">
                  {{--                                      <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Paycom')}}</span>
                                                        </span>--}}
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif

                                                    @if(\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="paypal" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/paypal.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Paypal')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="stripe" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/stripe.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Stripe')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="sslcommerz" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/sslcommerz.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('sslcommerz')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="instamojo" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/instamojo.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Instamojo')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="razorpay" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/rozarpay.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Razorpay')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="paystack" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/paystack.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Paystack')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="voguepay" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/vogue.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('VoguePay')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'payhere')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="payhere" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/payhere.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('payhere')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'ngenius')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="ngenius" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/ngenius.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('ngenius')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'iyzico')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="iyzico" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/iyzico.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Iyzico')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'nagad')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="nagad" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/nagad.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Nagad')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\BusinessSetting::where('type', 'bkash')->first()->value == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="bkash" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/bkash.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Bkash')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(\App\Addon::where('unique_identifier', 'african_pg')->first() != null && \App\Addon::where('unique_identifier', 'african_pg')->first()->activated)
                                                        @if(\App\BusinessSetting::where('type', 'mpesa')->first()->value == 1)
                                                            <div class="col-6 col-md-4">
                                                                <label class="aiz-megabox d-block mb-3">
                                                                    <input value="mpesa" class="online_payment"
                                                                           type="radio" name="payment_option"
                                                                           checked>
                                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                            <img src="{{ static_asset('assets/img/cards/mpesa.png')}}"
                                                                 class="img-fluid mb-2">
                                                            <span class="d-block text-center">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ translate('mpesa')}}</span>
                                                            </span>
                                                        </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if(\App\BusinessSetting::where('type', 'flutterwave')->first()->value == 1)
                                                            <div class="col-6 col-md-4">
                                                                <label class="aiz-megabox d-block mb-3">
                                                                    <input value="flutterwave"
                                                                           class="online_payment" type="radio"
                                                                           name="payment_option" checked>
                                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                            <img
                                                                src="{{ static_asset('assets/img/cards/flutterwave.png')}}"
                                                                class="img-fluid mb-2">
                                                            <span class="d-block text-center">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ translate('flutterwave')}}</span>
                                                            </span>
                                                        </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if(\App\BusinessSetting::where('type', 'payfast')->first()->value == 1)
                                                            <div class="col-6 col-md-4">
                                                                <label class="aiz-megabox d-block mb-3">
                                                                    <input value="payfast" class="online_payment"
                                                                           type="radio" name="payment_option"
                                                                           checked>
                                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/payfast.png')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('payfast')}}</span>
                                                        </span>
                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if(\App\Addon::where('unique_identifier', 'paytm')->first() != null && \App\Addon::where('unique_identifier', 'paytm')->first()->activated)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="paytm" class="online_payment"
                                                                       type="radio" name="payment_option" checked>
                                                                <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/paytm.jpg')}}"
                                                             class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Paytm')}}</span>
                                                        </span>
                                                    </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if(true)
                                                        @php
                                                            $digital = 0;
                                                            foreach(Session::get('cart') as $cartItem){
                                                                if($cartItem['digital'] == 1){
                                                                    $digital = 1;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($digital != 1)
                                                            <div class="col-6 col-md-4">
                                                                <label class="aiz-megabox d-block mb-3">
                                                                    <input value="cash_on_delivery"
                                                                           class="online_payment" type="radio"
                                                                           name="payment_option" checked>
                                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                            <img src="{{ static_asset('assets/img/cards/cod.png')}}"
                                                                 class="img-fluid w-md-60px mb-2">
                                                            <span class="d-block text-center">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ translate('Cash on Delivery')}}</span>
                                                            </span>
                                                        </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if (Auth::check())
                                                        @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null && \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                                                            @foreach(\App\ManualPaymentMethod::all() as $method)
                                                                <div class="col-6 col-md-4">
                                                                    <label class="aiz-megabox d-block mb-3">
                                                                        <input value="{{ $method->heading }}"
                                                                               type="radio" name="payment_option"
                                                                               onchange="toggleManualPaymentData({{ $method->id }})"
                                                                               data-id="{{ $method->id }}" checked>
                                                                        <span class="d-block p-3 aiz-megabox-elem">
                                                                <img src="{{ uploaded_asset($method->photo) }}"
                                                                     class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ $method->heading }}</span>
                                                                </span>
                                                            </span>
                                                                    </label>
                                                                </div>
                                                            @endforeach

                                                            @foreach(\App\ManualPaymentMethod::all() as $method)
                                                                <div id="manual_payment_info_{{ $method->id }}"
                                                                     class="d-none">
                                                                    @php echo $method->description @endphp
                                                                    @if ($method->bank_info != null)
                                                                        <ul>
                                                                            @foreach (json_decode($method->bank_info) as $key => $info)
                                                                                <li>{{ translate('Bank Name') }}
                                                                                    - {{ $info->bank_name }}
                                                                                    , {{ translate('Account Name') }}
                                                                                    - {{ $info->account_name }}
                                                                                    , {{ translate('Account Number') }}
                                                                                    - {{ $info->account_number}}
                                                                                    , {{ translate('Routing Number') }}
                                                                                    - {{ $info->routing_number }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </div>

                                                @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null && \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                                                    <div class="bg-white border mb-3 p-3 rounded text-left d-none">
                                                        <div id="manual_payment_description">

                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="pt-3 d-flex justify-content-center">
                                                    <label class="aiz-checkbox">
                                                        <input type="checkbox" checked required id="agree_checkbox">
                                                        <span class="aiz-square-check"></span>
                                                        <span
                                                            style="font-weight: 700">{{ translate('I agree to the')}}</span>
                                                    </label>
                                                </div>

                                                {{-- @if (Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                                                     <div class="separator mb-3">
                                         <span class="bg-white px-3">
                                             <span class="opacity-60">{{ translate('Or')}}</span>
                                         </span>
                                                     </div>
                                                     <div class="text-center py-4">
                                                         <div class="h6 mb-3">
                                                             <span
                                                                 class="opacity-80">{{ translate('Your wallet balance :')}}</span>
                                                             <span
                                                                 class="fw-600">{{ single_price(Auth::user()->balance) }}</span>
                                                         </div>
                                                         --}}{{--@if(Auth::user()->balance < $total)
                                                             <button type="button" class="btn btn-secondary" disabled>{{ translate('Insufficient balance')}}</button>
                                                         @else
                                                             <button  type="button" onclick="use_wallet()" class="btn btn-primary fw-600">{{ translate('Pay with wallet')}}</button>
                                                         @endif--}}{{--
                                                     </div>
                                                     <div class="pt-3 d-flex justify-content-center">
                                                         <label class="aiz-checkbox">
                                                             <input type="checkbox" checked required id="agree_checkbox">
                                                             <span class="aiz-square-check"></span>
                                                             <span
                                                                 style="font-weight: 700">{{ translate('I agree to the')}}</span>
                                                         </label>
                                                     </div>
                                                     <div class="d-flex justify-content-center">
                                                         <a href="{{ route('terms') }}"
                                                            style="color: #1da1f2">{{ translate('terms and conditions')}}</a>
                                                         <a href="{{ route('returnpolicy') }}"
                                                            style="color: #1cac3d; margin: 0 5%">{{ translate('return policy')}}</a>
                                                         <a href="{{ route('privacypolicy') }}"
                                                            style="color: #16a9c1">{{ translate('privacy policy')}}</a>
                                                     </div>
                                                 @endif--}}

                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12 mb-2">
                                                <a href="{{ route('home') }}" class="link link--style-3">
                                                    <i class="las la-arrow-left"></i>
                                                    {{ translate('Return to shop')}}
                                                </a>
                                            </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12  text-right">
                                            {{--<button type="button" onclick="submitOrder(this)"
                                                    class="btn btn-primary fw-600">{{ translate('Complete Order')}}</button>--}}
                                            {{--<button type="submit"
                                                    class="btn btn-primary fw-600" id="order-btn-submit" onclick="confirmPhoneOrder()">{{ translate('Complete Order')}}</button>--}}
                                            <input type="button" name="btn" value="{{ translate('Complete Order')}}" onclick="send_phone_sms()" id="submitBtn" {{--data-toggle="modal" data-target="#confirm-phone"--}} class="btn btn-primary fw-600" />
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    @include('frontend.partials.cart_summary')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="new-address-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('New Address')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                <textarea class="form-control textarea-autogrow mb-3"
                                          placeholder="{{ translate('Your Address')}}" rows="1" name="address"
                                          required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Country')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                            name="country" required>
                                        @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value ==
                            'area_wise_shipping')
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ translate('City')}}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                                name="city"
                                                required>
                                            @foreach (\App\City::get() as $key => $city)
                                                <option
                                                    value="{{ $city->name }}">{{ $city->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ translate('City')}}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control mb-3"
                                               placeholder="{{ translate('Your City')}}"
                                               name="city" value="" required>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Postal code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3"
                                           placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value=""
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Phone')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}"
                                           name="phone" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-phone" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('Confirm Code')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" id="sms-confirm-close-confirm" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" id="phone-sms-confirm" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Confirm Code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" id="confirm-code-confirm" placeholder="{{ translate('Confirm sms code')}}"
                                           name="confirm-code" value="" required>
                                    <h4 style="color: red" id="wrong-confirm-sms"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="telphone-submir-main-form" class="btn btn-primary">{{ translate('Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    {{-----------------STEP-1 start--------------------}}
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: '{{ csrf_token() }}',
                key: key,
                quantity: element.value
            }, function (data) {
                updateNavCart();
                $('#cart-summary').html(data);
            });
        }

        function showCheckoutModal() {
            $('#GuestCheckout').modal();
        }
    </script>
    {{-----------------STEP-1 end--------------------}}


    <script type="text/javascript">

        $('#telphone-submir-main-form').on('click',function(){
            let smsCodeConfirm = $('#confirm-code-confirm').val();
            let phone = $('#sublayerPhone').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: "{{ route('detail_send_verify_sms') }}",
                data: {'phone':phone,'smsCode':smsCodeConfirm},
                success: function(data){
                    $('#checkout-action-form').submit();
                },
                error:function (){
                    $('#wrong-confirm-sms').html('Sms kod xato kiritildi.Iltimos qaytadan urinib ko\'ring');
                }
            });
        });

        function send_phone_sms(){
            console.log("It is working");
            var phone = $('#sublayerPhone').val();
            console.log(phone);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: "{{ route('detail_send_confirm_sms') }}",
                data: {'phone':phone},
                success: function(data){
                    $('#confirm-phone').modal('show');
                },
                error:function (data){
                    console.log(data);
                }
            });
        }

        $('#sms-confirm-close-confirm').on('click',function (){
            $('#wrong-confirm-sms').html('');
        });

        function add_new_address() {
            $('#new-address-modal').modal('show');
        }


        $(document).ready(function () {
            $(".online_payment").click(function () {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                $('#checkout-form').submit();
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
            }
        }

        function submitOrder(el) {
            $(el).prop('disabled', true);
            if ($('#agree_checkbox').is(":checked")) {
                $('#checkout-form').submit();
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id) {
            $('#manual_payment_description').parent().removeClass('d-none');
            $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
        }

    </script>

@endsection
