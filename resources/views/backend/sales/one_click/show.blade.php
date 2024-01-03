@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
            <a href="{{route('one_click_orders.index')}}" class="btn btn-circle btn-info">Ortga qaytish</a>
        </div>
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
            </div>
            {{--            <div class="col-md-3 ml-auto">
                            <label for=update_payment_status"">{{translate('Payment Status')}}</label>
                                <select class="form-control aiz-selectpicker"  data-minimum-results-for-search="Infinity" id="update_payment_status">
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>{{translate('Paid')}}</option>
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{translate('Unpaid')}}</option>
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{translate('Unpaid')}}</option>
                            </select>
                        </div>--}}
            <div class="col-md-3 ml-auto">
                <label for=update_delivery_status"">{{translate('Delivery Status')}}</label>
                <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_one_click_status">
                    <option value="0"
                            @if ($one_click->status == 0) selected @endif>{{($one_click->status(0)['value'])}}</option>
                    <option value="1"
                            @if ($one_click->status == 1) selected @endif>{{($one_click->status(1)['value'])}}</option>
                    <option value="2"
                            @if ($one_click->status == 2) selected @endif>{{($one_click->status(2)['value'])}}</option>
                </select>
            </div>
        </div>
        <div class="row gutters-5 py-3 px-4">
            <div class="col text-center text-md-left">
                <address>
                    <strong class="text-main">
                        {{ $one_click->name }}</strong><br>
                    {{$one_click->phone}}<br>
                    {{$one_click->address}}<br>
                    {{$one_click->addition}}
                </address>

            </div>
            <div class="col-md-4 ml-auto">
                <table>
                    <tbody>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment Method')}}</td>
                        <td class="text-right text-info text-bold">    {{$one_click->payment_type ? 'Karta orqali onlayn to\'lov' : 'Mahsulotni olganda (naqd)'}}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment Status')}}</td>
                        <td class="text-right">
                            <span id="payment_status"
                                  class="badge badge-inline {{($one_click->status($one_click->status)['color'])}}">{{($one_click->status($one_click->status)['value'])}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Date')}}    </td>
                        <td class="text-right">{{ $one_click->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">Buyurtma narxi</td>
                        <td class="text-right">
                            {{ home_discounted_price($one_click->product->id) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">Uygacha</td>
                        <td class="text-right">{{$one_click->to_home? 'Ha': 'yo\'q'}}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{__('intend.xarid_qoidalari')}}:</td>
                        <td class="text-right">{{$one_click->agree_to_buy ? 'roziman' : 'rozi emasman' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="">
            <table class="table table-bordered aiz-table">
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

                            $photos = explode(',',$one_click->product->photos);
                        @endphp
                        <img
                            src="{{ uploaded_asset($photos[0]) }}"
                            data-src="{{ uploaded_asset($photos[0]) }}"
                            class="img-fit size-60px rounded ls-is-cached lazyloaded"
                            alt="{{$one_click->product->name  }}">
                    </td>
                    <td>
                        {{  $one_click->product->name  }}
                    </td>
                    <td>
                        1x
                    </td>
                    <td>{{ home_discounted_price($one_click->product->id) }}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_one_click_status').on('change', function () {
            var one_click_id = {{ $one_click->id }};
            var status = $('#update_one_click_status').val();
            var text_id = $('#payment_status');
            $.post('{{ route('update_one_click_status') }}', {
                _token: '{{ @csrf_token() }}',
                one_click_id: one_click_id,
                status: status
            }, function (data) {
                if (data == "0") {
                    text_id.html('Kutilmoqda');
                    text_id.removeClass();
                    text_id.addClass('badge badge-inline badge-info');
                } else if (data == "1") {
                    text_id.html('Rad etildi');
                    text_id.removeClass();
                    text_id.addClass('badge badge-inline badge-danger');
                } else {
                    text_id.html('Qabul qilindi');
                    text_id.removeClass();
                    text_id.addClass('badge badge-inline badge-success');
                }

                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });


    </script>
@endsection
