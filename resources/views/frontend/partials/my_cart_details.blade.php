    <div class="container">
        @if (Session::has('cart') && count(Session::get('cart')) > 0)
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card">
                        <div class="card-header bg-soft-primary">
                            <h3 class="fs-16 fw-600 mb-0">{{ translate('1. My Cart')}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="shadow-sm bg-white mb-3   text-left">
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
                                                <div class="col-lg-5 d-flex">
                                                    <span class="mr-2 ml-0">
                                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                             class="img-fit size-60px rounded"
                                                             alt="{{ $product->getTranslation('name') }}">
                                                    </span>

                                                    <span
                                                        class="fs-14 opacity-60">{{ $product_name_with_choice }}</span>
                                                </div>

                                                <div class="col-lg col-4 order-1 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Price') }}</span>
                                                    <span
                                                        class="fw-600 fs-16">{{ single_price($cartItem['price']) }}</span>
                                                </div>
                                                <div class="col-lg col-6 order-4 order-lg-0">
                                                    @if ($cartItem['digital'] != 1)
                                                        <div
                                                            class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0">
                                                            @if(!$cartItem['bonus'])
                                                                <button
                                                                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                    type="button" data-type="minus"
                                                                    data-field="quantity[{{ $key }}]">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                            @endif
                                                            <input type="text" name="quantity[{{ $key }}]"
                                                                   class="col border-0 text-center flex-grow-1 fs-16 input-number"
                                                                   placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                   min="1" max="10" readonly
                                                                   onchange="updateQuantity({{ $key }}, this)">
                                                            @if(!$cartItem['bonus'])
                                                                <button
                                                                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                    type="button" data-type="plus"
                                                                    data-field="quantity[{{ $key }}]">
                                                                    <i class="las la-plus"></i>
                                                                </button>

                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-lg col-4 order-3 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Total') }}</span>
                                                    <span
                                                        class="fw-600 fs-16 text-primary">{{ single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity']) }}</span>
                                                </div>
                                                <div class="col-lg-auto col-6 order-5 order-lg-0 text-right">
                                                    <a href="javascript:void(0)"
                                                       onclick="removeFromCartView(event, {{ $key }})"
                                                       class="btn btn-icon btn-sm btn-soft-primary btn-circle">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>


                                {{--<div class="px-3 py-2 mb-4 border-top d-flex justify-content-between">
                                    <span class="opacity-60 fs-15">{{ translate('Subtotal') }}</span>
                                    <span class="fw-600 fs-17">{{ single_price($total) }}</span>
                                </div>
    --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-header bg-soft-primary">
                            <h3 class="fs-16 fw-600 mb-0">{{translate('Items')}} soni : {{ count(Session::get('cart')) }}</h3>
                        </div>

                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                <tr class="cart-shipping">
                                    <th style="border-top: none;">{{translate('Tax')}}</th>
                                    <td class="text-right" style="border-top: none;">
                                        <span class="font-italic">{{ single_price($tax) }}</span>
                                    </td>
                                </tr>

                                <tr class="cart-shipping">
                                    <th>{{translate('Total Shipping')}}</th>
                                    <td class="text-right">
                                        <span class="font-italic">20000</span>
                                    </td>
                                </tr>
                                <tr class="cart-shipping">
                                    <th>{{__('Jami Buyutmalar')}}</th>
                                    <td class="text-right">
                                        <span class="font-italic">{{ single_price($total) }}</span>
                                    </td>
                                </tr>
                                @if (Session::has('coupon_discount'))
                                    <tr class="cart-shipping">
                                        <th>{{translate('Coupon Discount')}}</th>
                                        <td class="text-right">
                                                    <span
                                                        class="font-italic">{{ single_price(Session::get('coupon_discount')) }}</span>
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $total = $subtotal+$tax+$shipping;
                                    if(Session::has('coupon_discount')){
                                        $total -= Session::get('coupon_discount');
                                    }
                                @endphp

                                <tr class="cart-total">
                                    <th><span class="strong-600">{{translate('Total')}}</span></th>
                                    <td class="text-right">
                                        <strong><span>{{ single_price($total+20000) }}</span></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            {{--@if (Auth::check())
                                <a href="{{ route('checkout.detail_payment') }}"
                                   class="btn btn-primary btn-block fw-600">Buyurtmani rasmiylashtirish</a>
                            @else
                                <button class="btn btn-soft-dark btn-block fw-600"
                                        onclick="showCheckoutModal()">Rasmiylashtirish uchun tizimga kiring</button>
                            @endif--}}
                            <a href="{{ route('checkout.detail_payment') }}"
                               class="btn btn-primary btn-block fw-600">Buyurtmani rasmiylashtirish</a>
                        </div>
                    </div>

                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="shadow-sm bg-white p-4 rounded">
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
