@extends('frontend.layouts.app')

@section('content')
    @php
        // $status = $order->orderDetails->first()->delivery_status;

    @endphp
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body">
                            <div class="text-center py-4 mb-4">
                                <i class="la la-check-circle la-3x text-success mb-3"></i>
                                <h1 class="h3 mb-3 fw-600">{{ translate('Thank You for Your Order!')}}</h1>
                                <h2 class="h5">Tez orada siz bilan bog'lanamiz</h2>
                                {{--<h2 class="h5">{{ translate('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>--}}
                                {{--<p class="opacity-70 font-italic">{{  translate('A copy or your order summary has been sent to') }} {{ json_decode($order->shipping_address)->email }}</p>--}}
                            </div>
                            <div class="mb-4">
                                <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Summary')}}</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tr>
                                                <td class="w-50 fw-600">{{translate('Customer')}}:</td>
                                                <td>{{ $data[0]->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('Telefon raqam')}}:</td>
                                                <td>{{ $data[0]->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('Manzili')}}:</td>
                                                <td>{{ $data[0]->address }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('Qo\'shimcha')}}:</td>
                                                <td>{{ $data[0]->addition }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('Uyga yetkazib berish')}}:</td>
                                                <td>{{ $data[0]->to_home? 'Ha': 'yo\'q' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('Promo kod')}}:</td>
                                                <td>{{ $data[0]->promo_kod ?? 'yo\'q' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{translate('Payment Method')}}:</td>
                                                <td>{{ $data[0]->payment_type ? 'Karta orqali onlayn to\'lov' : 'Mahsulotni olganda (naqd)' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{__('intend.xarid_qoidalari')}}:</td>
                                                <td>{{ $data[0]->agree_to_buy ? __('intend.rozilik') : 'rozi emasman' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{translate('Order Date')}}:</td>
                                                <td>{{ $data[0]->created_at }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                        <tr>
                                            <th>{{ translate('Rasm')}}</th>
                                            <th>{{ translate('Product')}}</th>
                                            <th>{{ translate('Quantity')}}</th>
                                            <th>{{ translate('Price')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                @php

                                                    $photos = explode(',',$data[0]->product->photos);
                                                @endphp
                                                <img
                                                    src="{{ uploaded_asset($photos[0]) }}"
                                                    data-src="{{ uploaded_asset($photos[0]) }}"
                                                    class="img-fit size-60px rounded ls-is-cached lazyloaded"
                                                    alt="{{$data[0]->product->name  }}">
                                            </td>
                                            <td>
                                                {{  $data[0]->product->name  }}
                                            </td>
                                            <td>
                                                1x
                                            </td>
                                            <td>{{ home_discounted_price($data[0]->product->id) }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
