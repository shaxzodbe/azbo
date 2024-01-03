@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{__('Set Point for Product')}}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('product_point.update', $product->id) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="col-form-label">{{__('Set Point')}}</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" min="0" step="0.01" class="form-control" name="point" value="{{ $product->earn_point }}" placeholder="100" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button class="btn btn-sm btn-primary" type="submit">{{__('Save')}}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
