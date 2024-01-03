@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
    </div>
    <div class="">
        <div class="">
            <form class="form form-horizontal mar-top" action="{{ route('bonus_products.update', $product->id) }}"
                method="POST" enctype="multipart/form-data" id="choice_form">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="card">
                    <ul class="nav nav-tabs nav-fill border-light">
                        @foreach (\App\Language::all() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                    href="{{ route('products.admin.edit', ['id' => $product->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                                        class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Product Name') }} <i
                                    class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ translate('Product Name') }}"
                                    value="{{ $product->getTranslation('name', $lang) }}" required>
                            </div>
                        </div>
                        <div class="form-group row" id="category">
                            <label class="col-lg-3 col-from-label">{{ translate('Category') }}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                    data-selected="{{ $product->category_id }}" data-live-search="true" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}
                                        </option>
                                        @foreach ($category->childrenCategories as $childCategory)
                                            @include('categories.child_category', ['child_category' => $childCategory])
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-lg-3 col-from-label">{{ translate('Brand') }}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                    data-live-search="true">
                                    <option value="">{{ 'Select Brand' }}</option>
                                    @foreach (\App\Brand::all() as $brand)
                                        <option value="{{ $brand->id }}" @if ($product->brand_id == $brand->id) selected @endif>
                                            {{ $brand->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Unit') }} <i
                                    class="las la-language text-danger" title="{{ translate('Translatable') }}"></i>
                            </label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="unit"
                                    placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}"
                                    value="{{ $product->getTranslation('unit', $lang) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Minimum Qty') }}</label>
                            <div class="col-lg-8">
                                <input type="number" lang="en" class="form-control" name="min_qty"
                                    value="@if ($product->min_qty <= 1){{ 1 }}@else{{ $product->min_qty }}@endif" min="1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Tags') }}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags"
                                    value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}"
                                    data-role="tagsinput" required>
                            </div>
                        </div>
                        @php
                            $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                        @endphp
                        @if ($pos_addon != null && $pos_addon->activated == 1)
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Barcode') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="barcode"
                                        placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                                </div>
                            </div>
                        @endif

                        @php
                            $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                        @endphp
                        @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Refundable') }}</label>
                                <div class="col-lg-8">
                                    <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                        <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif>
                                        <span class="slider round"></span></label>
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Gallery Images') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="photos" value="{{ $product->photos }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                <small>(290x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>

                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Unit price') }}</label>
                            <div class="col-lg-6">
                                <input type="text" placeholder="{{ translate('Unit price') }}" name="unit_price"
                                    class="form-control" value="{{ $product->unit_price }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Purchase price') }}</label>
                            <div class="col-lg-6">
                                <input type="number" lang="en" min="0" step="0.01"
                                    placeholder="{{ translate('Purchase price') }}" name="purchase_price"
                                    class="form-control" value="{{ $product->purchase_price }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Discount') }}</label>
                            <div class="col-lg-6">
                                <input type="number" lang="en" min="0" step="0.01"
                                    placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                    value="{{ $product->discount }}" required>
                            </div>
                            <div class="col-lg-3">
                                <select class="form-control aiz-selectpicker" name="discount_type" required>
                                    <option value="amount" <?php if ($product->discount_type == 'amount') {
    echo 'selected';
} ?>>{{ translate('Flat') }}</option>
                                    <option value="percent" <?php if ($product->discount_type == 'percent') {
    echo 'selected';
} ?>>{{ translate('Percent') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="quantity">
                            <label class="col-lg-3 col-from-label">{{ translate('Quantity') }}</label>
                            <div class="col-lg-6">
                                <input type="number" lang="en" value="{{ $product->current_stock }}" step="1"
                                    placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control"
                                    required>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Description') }} <i
                                    class="las la-language text-danger"
                                    title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-lg-9">
                                <textarea class="aiz-text-editor"
                                    name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="meta_title"
                                    value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-lg-8">
                                <textarea name="meta_description" rows="8"
                                    class="form-control">{{ $product->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Meta Images') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Slug') }}</label>
                            <div class="col-md-8">
                                <input type="text" placeholder="{{ translate('Slug') }}" id="slug" name="slug"
                                    value="{{ $product->slug }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-info">{{ translate('Update Product') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

