@extends('frontend.layouts.app')

@section('content')

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')
                <div class="aiz-user-panel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Purchase History') }}</h5>
                        </div>
                        @if (count($selected_intend) > 0)
                            <div class="card-body">
                                <table class="table aiz-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Product Name</th>
                                        <th data-breakpoints="md">Product Price</th>
                                        <th data-breakpoints="md">Product Monthly Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($selected_intend as $intend)
                                        <tr>
                                            <td>
                                                <img class="w-80px"
                                                     src="{{ uploaded_asset($intend->products->thumbnail_img) }}"
                                                     alt="">
                                            </td>
                                            <td>
                                                <a href="{{route('product',['slug'=>$intend->products->slug])}}" class="text-black">
                                                    <h6>{{$intend->products->name}}</h6>
                                                </a>
                                            </td>
                                            <td>{{$intend->product_price}}</td>
                                            <td>{{$intend->product_monthly_price}}</td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                                <div class="aiz-pagination">
                                    {{ $selected_intend->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    @include('modals.delete_modal')

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

@endsection
