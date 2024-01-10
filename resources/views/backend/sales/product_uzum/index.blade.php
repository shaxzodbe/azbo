@extends('backend.layouts.app')

@section('content')
    @php
        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
    @endphp

    <div class="card">
        <form class="" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th data-breakpoints="lg">{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Address')}}</th>
{{--                    <th data-breakpoints="lg">{{__('intend.toHome')}}</th>
                    <th data-breakpoints="lg">{{__('Promo kod')}}</th>--}}
                    <th data-breakpoints="lg">{{ __('pagination.Created_at') }}</th>
                    <th data-breakpoints="lg">{{translate('Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Options')}}</th>

                </tr>
                </thead>
                <tbody>
                @foreach ($productUzum as $key => $one_click_order)
                    @if($one_click_order != null)
                        <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                @if($one_click_order->product)
                                    @php
                                        $photos = explode(',',$one_click_order->product->photos);
                                    @endphp
                                    <span class="d-flex align-items-center">
                                        <img
                                            src="{{ uploaded_asset($photos[0]) }}"
                                            data-src="{{ uploaded_asset($photos[0]) }}"
                                            class="img-fit size-60px rounded ls-is-cached lazyloaded"
                                            alt="{{$one_click_order->product->name  }}">
                                        <span class="minw-0 pl-2 flex-grow-1">
                                            <span class="fw-600 mb-1 text-truncate-2">
                                                    {{  $one_click_order->product->name  }}
                                            </span>
                                            <span class="">1x</span>
                                            <span
                                                class="">{{ home_discounted_price($one_click_order->product->id) }}</span>
                                        </span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $user = \App\User::where('id','=',$one_click_order->user_id)->first();
                                        if (!empty($user)){
                                            echo $user->name;
                                        }else{
                                         echo "empty";
                                        }
                                @endphp
                            </td>
                            <td>
                                @if(!empty($one_click_order->user->phone))
                                {{ $one_click_order->user->phone }}
                                @else
                                    {{ 'Empty' }}
                                @endif
                            </td>
                            <td>
                               email {{--{{ $one_click_order->user->email }}--}}
                            </td>
                            <td>
                                {{ $one_click_order->created_at }}
                            </td>
                            <td class="text-right">
                            <span id="payment_status"
                                  class="badge badge-inline {{($one_click_order->status($one_click_order->status)['color'])}}">{{($one_click_order->status($one_click_order->status)['value'])}}</span>
                            </td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="{{ route('indend.show', encrypt($one_click_order->id)) }}"
                                   title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   data-href="{{route('one_click_orders.destroy', $one_click_order->id)}}"
                                   title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $productUzum->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
