@extends('frontend.layouts.app')

@section('content')

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')

                <div class="aiz-user-panel">

                    <div class="aiz-titlebar mt-2 mb-4">
                      <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="h3">{{ translate('Bulk Products Price Upload') }}</h1>
                        </div>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table aiz-table mb-0" style="font-size:14px; background-color: #cce5ff; border-color: #b8daff">
                                <tr>
                                    <td>{{ translate('Download the last x products, example 100 last products') }}:</td>
                                </tr>
                                <tr >
                                    <td>{{ translate('Update the unit_price and purchase_price.') }}:</td>
                                </tr>
                                <tr>
                                    <td> {{ translate('Upload the excel file.') }}:</td>
                                </tr>
                            </table>
                            
                            <br>

                            <form action="{{route('Bulk_Product_Price.export')}}" method="GET">
                                
                                <div class="form-group row">
                                                <label class="col-md-2 col-from-label">{{ translate('Number of products') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-4">
                                                    <input type="number" lang="en" class="form-control" name="num_product" value="100" 
                                                        required>
                                                </div>
                                            </div>

                                <div class="">
                                <button type="submit" class="btn btn-info">{{ translate('Download CSV')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
            
            

                    <div class="card">
                        <div class="card-header">
                            <div class="col text-center text-md-left">
                                <h5 class="mb-md-0 h6">{{ translate('Upload CSV File') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" action="{{ route('bulk_product_price_upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ translate('CSV') }}</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                    						<label class="custom-file-label">
                    							<input type="file" name="bulk_file" class="custom-file-input" required>
                    							<span class="custom-file-name">{{ translate('Choose File')}}</span>
                    						</label>
                    					</div>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-right">
                                    <button type="submit" class="btn btn-primary">{{translate('Upload CSV')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection

