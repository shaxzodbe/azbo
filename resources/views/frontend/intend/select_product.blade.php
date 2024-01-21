@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}"/>
    <meta property="og:type" content="og:product"/>
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}"/>
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}"/>
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}"/>
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}"/>
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}"/>
    <meta property="product:price:currency"
          content="{{ \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code }}"/>
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @php

        @endphp
    <section class="mb-4 pt-3">
        <div class="container bagema">
            <div class="bg-white shadow-sm rounded p-3">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 mb-4">
                        <div class="sticky-top z-3 row gutters-10">
                            @php
                                $photos = explode(',',$detailedProduct->photos);
                            @endphp
                            <div class="col order-1 order-md-2">
                                <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb'
                                     data-fade='true'>
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box img-zoom rounded">
                                                <img
                                                    class="img-fluid lazyload"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                    @foreach ($photos as $key => $photo)
                                        <div class="carousel-box img-zoom rounded">
                                            <img
                                                class="img-fluid lazyload"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 col-md-auto w-md-80px order-2 order-md-1 mt-3 mt-md-0">
                                <div class="aiz-carousel product-gallery-thumb" data-items='5'
                                     data-nav-for='.product-gallery' data-vertical='true' data-vertical-sm='false'
                                     data-focus-select='true' data-arrows='true'>
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box c-pointer border p-1 rounded"
                                                 data-variation="{{ $stock->variant }}">
                                                <img
                                                    class="lazyload mw-100 size-50px mx-auto"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                    @foreach ($photos as $key => $photo)
                                        <div class="carousel-box c-pointer border p-1 rounded">
                                            <img
                                                class="lazyload mw-100 size-50px mx-auto"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        <div class="text-left">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h1 class="mb-2 fs-20 fw-600">
                                        {{ $detailedProduct->getTranslation('name') }}
                                    </h1>
                                    @php
                                        $total = 0;
                                        $total += $detailedProduct->reviews->count();
                                    @endphp
                                    <span class="rating">
                                        {{ renderStarRating($detailedProduct->rating) }}
                                    </span>
                                    <span class="ml-1 opacity-50">({{ $total }} {{ translate('reviews')}})</span>
                                </div>
                                <div class="col-6 text-right">
                                    @php
                                        $qty = 0;
                                        if($detailedProduct->variant_product){
                                            foreach ($detailedProduct->stocks as $key => $stock) {
                                                $qty += $stock->qty;
                                            }
                                        }
                                        else{
                                            $qty = $detailedProduct->current_stock;
                                        }
                                    @endphp
                                    <img src="{{static_asset('assets/img/logo-intend.png')}}" alt="">
                                </div>
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <small class="mr-2 opacity-50">{{ translate('Sold by')}}: </small><br>
                                    @if ($detailedProduct->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                                           class="text-reset">{{ $detailedProduct->user->shop->name }}</a>
                                    @else
                                        {{  translate('Inhouse product') }}
                                    @endif
                                </div>
                                @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary"
                                                onclick="show_chat_modal()">{{ translate('Message Seller')}}</button>
                                    </div>
                                @endif

                                @if ($detailedProduct->brand != null)
                                    <div class="col-auto">
                                        <a href="{{ route('products.brand',$detailedProduct->brand->slug) }}">
                                            <img src="{{ uploaded_asset($detailedProduct->brand->logo) }}"
                                                 alt="{{ $detailedProduct->brand->getTranslation('name') }}"
                                                 height="30">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            @if(home_price($detailedProduct->id) != home_discounted_price($detailedProduct->id))

                                <div class="row no-gutters mt-3">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="fs-20 opacity-60">
                                            <del>
                                                {{ home_price($detailedProduct->id) }}
                                                @if($detailedProduct->unit != null)
                                                    <span>/{{ $detailedProduct->getTranslation('unit') }}</span>
                                                @endif
                                            </del>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters my-2">
                                    <div class="col-sm-2">
                                        <div class="opacity-50">{{ translate('Discount Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="">
                                            <strong class="h2 fw-600 text-primary">
                                                {{ home_discounted_price($detailedProduct->id) }}
                                            </strong>
                                            @if($detailedProduct->unit != null)
                                                <span
                                                    class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row no-gutters mt-3">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="">
                                            <strong class="h2 fw-600 text-primary">
                                                {{ home_discounted_price($detailedProduct->id) }}
                                            </strong>
                                            @if($detailedProduct->unit != null)
                                                <span
                                                    class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated && $detailedProduct->earn_point > 0)
                                <div class="row no-gutters mt-4">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{  translate('Club Point') }}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div
                                            class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border">
                                            <span class="strong-700">{{ $detailedProduct->earn_point }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

                                @if ($detailedProduct->choice_options != null)
                                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)



                                        <div class="row no-gutters"
                                             @if(count($choice->values) == 1) style="display:none" @endif>

                                            <div class="col-sm-2">
                                                @php
                                                    $ao_attribute_name = \App\Attribute::find($choice->attribute_id) ? \App\Attribute::find($choice->attribute_id)->getTranslation('name') : null;

                                                @endphp

                                                <div class="opacity-50 my-2">{{ $ao_attribute_name }}:</div>


                                            </div>
                                            <div class="col-sm-10">
                                                <div class="aiz-radio-inline">
                                                    @foreach ($choice->values as $key => $value)
                                                        <label class="aiz-megabox pl-0 mr-2">
                                                            <input
                                                                type="radio"
                                                                name="attribute_id_{{ $choice->attribute_id }}"
                                                                value="{{ $value }}"
                                                                @if($key == 0) checked @endif
                                                            >
                                                            <span
                                                                class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2">
                                                    @php
                                                        $attr = \App\Attribute::find($value);


                                                    @endphp

                                                                {{-- $value --}} {{ $attr->getTranslation('name')}}
                                                    </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif

                                @if (count(json_decode($detailedProduct->colors)) > 0)
                                    <div class="row no-gutters">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Color')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                                    <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                                           data-title="{{ \App\Color::where('code', $color)->first()->name }}">
                                                        <input
                                                            type="radio"
                                                            name="color"
                                                            value="{{ \App\Color::where('code', $color)->first()->name }}"
                                                            @if($key == 0) checked @endif>
                                                        <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                        <span class="size-30px d-inline-block rounded" style="background: {{ $color }};"></span>
                                                    </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                @endif

                            <!-- Quantity + Add to cart -->
                                <div class="row no-gutters" style="border-top: none;">

                                    <div class="col-sm-10">
                                        <div class="product-quantity d-flex align-items-center">
                                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3"
                                                 style="width: 130px; display: none">
                                                <button class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="minus" data-field="quantity"
                                                        disabled="" style="display: none;">
                                                    <i class="las la-minus"></i>
                                                </button>
                                                <input type="text" name="quantity"
                                                       class="col border-0 text-center flex-grow-1 fs-16 input-number"
                                                       placeholder="1" value="{{ $detailedProduct->min_qty }}"
                                                       min="{{ $detailedProduct->min_qty }}" max="10" readonly>
                                                <button class="btn  col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus" disabled data-field="quantity">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Total Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="product-price">
                                            <strong id="chosen_price1" class="h4 fw-600 text-primary">
                                                {{ $calculate[0]['prices'][0]['price'] }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>


                                <!-- end Quantity + Add to cart -->
                                <!-- $detailedProduct->monthly_price && -->

                                @if(\App\BusinessSetting::where('type', 'monthly_payment')->first()->value == 1)

                                    <hr>
                                    <!-- monthly price -->
                                    <div class="row no-gutters pb-3">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Monthly Price')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="">
                                                <strong class="h4 fw-600 text-primary" id="monthly_price1">
                                                    {{ $calculate[0]['prices'][0]['per_month'] }}
                                                </strong>

                                                <span class="opacity-70" id="monthly_percent"
                                                      style="display:none"> </span>

                                            </div>
                                        </div>

                                        <input type="hidden" id="currency_symbol" value="{{ currency_symbol() }}">
                                        <input type="hidden" name="installment_id" id="installment_id" value="0">


                                        <div class="btn-toolbar pt-2" role="toolbar"
                                             aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-2 installments" role="group"
                                                 aria-label="First group">
                                                @php
                                                    $instalments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value);
                                                    $instalments = array_filter($instalments, function($i) { return $i->active; });
                                                @endphp

                                                @foreach ($instalments as $k => $instalment)
                                                    @if($instalment->period ==12)
                                                        <button onclick="changeInstalemnt(this)"
                                                                data-profit="{{ $detailedProduct->profit }}"
                                                                data-percent="{{ $instalment->value}}"
                                                                data-installment_id="{{ $instalment->id }}"
                                                                data-period="{{$instalment->period}}"
                                                                style="font-weight: 600" type="button"
                                                                class="btn btn-sm btn-outline-warning text-dark px-3 instalment-btn active {{--{{ $k == array_key_last($instalments) ? 'active' : ''}}--}}">{{ translate($instalment->label) }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end monthly price -->
                                @endif

                            </form>

                            <div class="mt-3 d-flex">
                                @dd($qty)
                                @if ($qty > 0)
                                    <a href="{{route('home')}}" class="btn btn-soft-primary mr-2 add-to-cart fw-600"
                                       {{--onclick="addToCart()"--}}>
                                        <span class="d-none d-md-inline-block"> {{ __('intend.home')}}</span>
                                    </a>
                                    @if(Auth::check())

                                    <!-- && $detailedProduct->added_by == 'admin' -->
                                        @if(\App\BusinessSetting::where('type', 'monthly_payment')->first()->value == 1)
                                            <div id="smsConfirmModal">
                                                <form action="{{ route('intent_order_create') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{$detailedProduct->id}}"
                                                           name="product">
                                                    <input type="hidden"
                                                           value="{{$calculate[0]['prices'][0]['duration']}}"
                                                           name="calculate">
                                                    <button class="btn btn-info fw-600"
                                                            type="submit">{{ __('intend.confirmation') }}</button>
                                                </form>
                                            </div>
                                            <br>
                                            <div class="helper"></div>
                                        @endif

                                    @else

                                    <!-- && $detailedProduct->added_by == 'admin' -->
                                        @if(\App\BusinessSetting::where('type', 'monthly_payment')->first()->value == 1)
                                            <a type="button" class="btn btn-info buy-now fw-600"
                                               onclick="showCheckoutModal()">
                                                <i class="la-lashopping-cart"></i> {{ translate('Pay in installments') }}
                                            </a>
                                        @endif
                                    @endif
                                @else
                                    <button type="button" class="btn btn-secondary fw-600" disabled>
                                        <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock')}}
                                    </button>
                                @endif
                            </div>

                            @php
                                $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                                $refund_sticker = \App\BusinessSetting::where('type', 'refund_sticker')->first();
                            @endphp
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1 && $detailedProduct->refundable)
                                <div class="row no-gutters mt-4">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Refund')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <a href="{{ route('returnpolicy') }}" target="_blank">
                                            @if ($refund_sticker != null && $refund_sticker->value != null)
                                                <img src="{{ uploaded_asset($refund_sticker->value) }}" height="36">
                                            @else
                                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}"
                                                     height="36">
                                            @endif
                                        </a>
                                        <a href="{{ route('returnpolicy') }}" class="ml-2" target="_blank">View
                                            Policy</a>
                                    </div>
                                </div>
                            @endif
                            <div class="row no-gutters mt-4">
                                <div class="col-sm-2">
                                    <div class="opacity-50 my-2">{{ translate('Share')}}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title"
                                   value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required
                                      placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600"
                                data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <input type="text"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}"
                                           name="email" id="email">
                                @else
                                    <input type="email"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{  translate('Email') }}"
                                           name="email">
                                @endif
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                       placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}"
                                       class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit"
                                        class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                           class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                           class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="GuestCheckout">
        <div class="modal-dialog modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
                                \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <input type="text"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}"
                                           name="email" id="email">
                                @else
                                    <input type="email"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{  translate('Email') }}"
                                           name="email">
                                @endif
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
                                \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                       placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}"
                                       class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit"
                                        class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                    </div>
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                        <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                    </div>
                    @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 ||
                    \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 ||
                    \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                        <div class="separator mb-3">
                            <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                        </div>
                        <ul class="list-inline social colored text-center mb-3">
                            @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="smsConfirm">
        <div class="modal-dialog modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ __('intend.smsConfirm')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3" id="formData">
                        <form class="form-default" role="form" action="{{ route('intent_cheque_confirm') }}"
                              method="POST">
                            @csrf

                            <input type="hidden" value="{{ $detailedProduct->id }}" name="product_id">
                            <div class="form-group">
                                <input type="password" name="code" class="form-control h-auto form-control-lg"
                                       placeholder="{{ translate('Password')}}">
                                <strong style="color: red;display: none;" class="error-confirm"></strong>
                                <strong style="color: green;display: none;" class="success-confirm"></strong>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12 text-right">
                                    <a href="{{ route('intent_cheque_resend') }}"
                                       id="target"
                                       class="text-reset resetSms opacity-60 fs-14">{{ __('intend.resend')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit"
                                        class="btn btn-primary btn-block fw-600">{{ __('intend.smsConfirm')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script type="text/javascript">
        function payInInstallments() {
            var f = document.getElementById('option-choice-form');
            f.action = '/installments';
            f.method = 'POST';
            f.submit();
        }


        function changeInstalemnt(el) {

            if (el.classList.contains('active')) {
                return;
            }
            var instalemnts_btn = document.querySelectorAll('.instalment-btn');
            instalemnts_btn.forEach(element => {
                element.classList.remove('active');
            });

            el.classList.add('active');
            var percent = parseInt(el.dataset.percent);
            var period = parseInt(el.dataset.period);
            var profit = parseInt(el.dataset.profit);

            var installment_id = el.dataset.installment_id;

            $('#installment_id').val(installment_id);

            var monthly_price = document.getElementById('monthly_price');
            var monthly_percent = document.getElementById('monthly_percent')

            var chosen_price = document.getElementById('chosen_price');
            chosen_price = parseInt(chosen_price.innerText.replace(/ +/g, ""));

            var percent_price = chosen_price * (percent / 100);
            var profit_price = chosen_price * (profit / 100);
            var calcul = (chosen_price + percent_price + profit_price) / period;

            final_price = Math.round(calcul)

            monthly_price.innerText = final_price.toLocaleString() + ' ' + document.getElementById('currency_symbol').value;
            monthly_percent.innerText = ' (' + el.dataset.percent + '%)';

        }

        $(document).ready(function () {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
            $('#chat_modal').modal('show');
            @else
            $('#login_modal').modal('show');
            @endif
        }


        function showCheckoutModal() {
            $('#GuestCheckout').modal();
        }

        $(document).ready(function () {
            $('#smsConfirmModal form').submit(function (event) {
                event.preventDefault();
                var form_data = $(this).serialize();
                $.post('/intent-order-create', form_data, function (data) {
                    console.log(data.success)
                    if (data.success) {
                        $('#smsConfirm').modal();
                    }

                });
            });
            $('#formData form').submit(function (event) {
                event.preventDefault();
                var form_data = $(this).serialize();
                $.post('/intent-cheque-confirm', form_data, function (data) {

                    if (data.success == false) {
                        $('.error-confirm').show().text(data.error.message);
                    } else {
                        $('#smsConfirm').modal('hide');
                        AIZ.plugins.notify('success', "{{__('intend.installment')}}");
                    }
                });
            });


            $("#target").click(function (event) {

                event.preventDefault();
                var form_data = $(this).serialize();
                $.get('/intent-cheque-resend', form_data, function (data) {
                    $('.error-email').hide();
                    $('.resetSms').show().text("{{__('intend.send_egain')}}");
                });

            });
        });


    </script>
@endsection
