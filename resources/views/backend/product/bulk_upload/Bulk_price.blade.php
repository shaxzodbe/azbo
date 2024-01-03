@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Product Price Bulk Upload')}}</h5>
        </div>
        <div class="card-body">
            <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                <strong> Steps: </strong>
                <p>1. {{ translate('Download the last x products, example 100 last products') }}</p>
                <p>2. {{ translate('Update the unit_price and purchase_price.') }}</p>
                <p>3. {{ translate('Upload the excel file.') }}</p>
            </div>
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6"><strong>{{translate('Upload Product Price File')}}</strong></h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('bulk_product_upload_price') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-9">
                        <div class="custom-file">
    						<label class="custom-file-label">
    							<input type="file" name="bulk_file" class="custom-file-input" required>
    							<span class="custom-file-name">{{ translate('Choose File')}}</span>
    						</label>
    					</div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-info">{{translate('Upload CSV')}}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
