@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Product') }}</h5>
    </div>
    <div class="">
        <div class="">
            <form class="form form-horizontal mar-top" action="{{ route('products.store') }}" method="POST"
                enctype="multipart/form-data" id="choice_form">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
                            </div>
                        </div>
                        <div class="form-group row" id="category">
                            <label class="col-md-3 col-from-label">{{ translate('Category') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                    data-live-search="true" required>
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
                            <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                    data-live-search="true">
                                    <option value="">{{ 'Select Brand' }}</option>
                                    @foreach (\App\Brand::all() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>
                            <div class="col-md-8">
                                

                                <select class="form-control aiz-selectpicker" name="unit" data-live-search="true">
                                   
                                    @foreach (['KG','QTY','SM','L','M','PC'] as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Minimum Qty') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Tags') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                    placeholder="{{ translate('Type and hit enter to add a tag') }}" required>
                                <small
                                    class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                            </div>
                        </div>

                        @php
                            $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                        @endphp
                        @if ($pos_addon != null && $pos_addon->activated == 1)
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="barcode"
                                        placeholder="{{ translate('Barcode') }}">
                                </div>
                            </div>
                        @endif

                        @php
                            $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                        @endphp
                        @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Refundable') }}</label>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="refundable" checked>
                                        <span></span>
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
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Gallery Images') }}
                                <small>(600x600)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="photos" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('These images are visible in product details page gallery. Use 600x600 sizes images.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                <small>(300x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="thumbnail_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Video Provider') }}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube">{{ translate('Youtube') }}</option>
                                    <option value="dailymotion">{{ translate('Dailymotion') }}</option>
                                    <option value="vimeo">{{ translate('Vimeo') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link"
                                    placeholder="{{ translate('Video Link') }}">
                                <small
                                    class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Variation') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{ translate('Colors') }}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>
                                    @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
                                        <option value="{{ $color->code }}"
                                            data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>">
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{ translate('Attributes') }}" disabled>
                            </div>

                            <div class="col-md-8">
                                <select name="choice_attributes[]" id="choice_attributes"
                                    class="form-control aiz-selectpicker" data-selected-text-format="count"
                                    data-live-search="true" multiple
                                    data-placeholder="{{ translate('Choose Attributes') }}">
                                    @foreach (\App\Attribute::whereNull('parent_id')->get() as $key => $attribute)
                                        @php
                                            $children = $attribute->children()->get();
                                            
                                            $children_trans = [];
                                            foreach ($children as $child) {
                                                array_push($children_trans, [
                                                    'id' => $child->id,
                                                    'name' => $child->getTranslation('name'),
                                                ]);
                                            }
                                        @endphp

                                        <option value="{{ $attribute->id }}"
                                            data-children="{{ json_encode($children_trans) }}">
                                            {{ $attribute->getTranslation('name') }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}
                            </p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Purchase price') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Purchase price') }}" name="purchase_price"
                                    class="form-control" required>
                            </div>
                        </div>
                        <!--
             <div class="form-group row">
              <label class="col-md-3 col-from-label">{{ translate('Tax') }} <span class="text-danger">*</span></label>
              <div class="col-md-6">
               <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}"
                name="tax" class="form-control" required>
              </div>
              <div class="col-md-3">
               <select class="form-control aiz-selectpicker" name="tax_type">
                <option value="amount">{{ translate('Flat') }}</option>
                <option value="percent">{{ translate('Percent') }}</option>
               </select>
              </div>
             </div>

             -->
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control aiz-selectpicker" name="discount_type">
                                    <option value="amount">{{ translate('Flat') }}</option>
                                    <option value="percent">{{ translate('Percent') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="quantity">
                            <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="1"
                                    placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Profit: </label>
                            <div class="col-md-6">
                                <input type="number" name="profit" id="profit" class="form-control" value="0">
                            </div>
                        </div>
                        <br>
                        <div class="sku_combination" id="sku_combination">

                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Product characteristics  --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Characteristics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Characteristics') }}</label>
                            <div class="col-lg-9">
                                <textarea class="aiz-text-editor" name="characteristics" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end product characteristics --}}

                @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Shipping Cost') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <h5 class="mb-0 h6">{{ translate('Free Shipping') }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Status') }}</label>
                                        <div class="col-md-8">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="radio" name="shipping_type" value="free" checked>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <h5 class="mb-0 h6">{{ translate('Flat Rate') }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Status') }}</label>
                                        <div class="col-md-8">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="radio" name="shipping_type" value="flat_rate" checked>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Shipping cost') }}</label>
                                        <div class="col-md-8">
                                            <input type="number" lang="en" min="0" value="0" step="0.01"
                                                placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('PDF Specification') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="document">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="pdf" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- alifshop -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Alifshop </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">Category: </label>
                            <div class="col-md-8">
                                <select class="form-control selectpicker" data-live-search="true" title="Nothing selected"
                                    id="alifshop_category_id" name="alifshop_category_id" disabled>
                                    @forelse (\App\AlifshopCategory::where('is_active',true)->get() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }} </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="alifshop_active">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">Brand : </label>
                            <div class="col-md-8">
                                <select class="form-control selectpicker " data-live-search="true" title="Nothing selected"
                                    id="alifshop_brand_id" name="alifshop_brand_id" disabled>
                                    @forelse (\App\AlifshopBrand::all() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }} </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">Visible:</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="alifshop_visible">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end alifshop -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"
                                    placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(\App\BusinessSetting::where('type', 'bonus_product_activation')->first()->value)
                <!-- Bonus product  section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Bonus Product') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Product name') }}: </label>
                            <div class="col-md-8">
                                <select name="bonus_product_id" id="" class="form-control  aiz-selectpicker" data-live-search="true">
                                    <option selected disabled>Select from list </option>
                                    @forelse (\App\Product::where('bonus', true)->get() as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->getTranslation('name') }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bonus_any_quantity" class="col-md-3 col-form-label">{{ translate('One bonus')}} ? :</label>
                            <div class="col-lg-8">
                                <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                    <input type="checkbox" name="bonus_any_quantity">
                                    <span class="slider round"></span></label>
                                </label>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Condition') }}:</label>
                            <div class="col-md-8">
                                <select name="bonus_condition" class="form-control">
                                    @foreach (range(1, 9) as $n)
                                        <option value="{{ $n }}" >{{ $n }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end Bonus product section -->

                @endif
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-primary">{{ translate('Save Product') }}</button>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('script')

    <script type="text/javascript">
        function add_more_customer_choice_option(el, i, name) {
            var d = el.attr('data-children');
            var children = JSON.parse(d);



            if (true) {
                var div_select =
                    `
									<div class="form-group row"> 
										<div class="col-md-3">
											<input type="hidden" name="choice_no[]" value="${i}">
											<input type="text" class="form-control" name="choice[]" value="${name}" placeholder="{{ translate('Choice Title') }}" readonly>
										</div>
										<div class="col-md-8">
											<select name="choice_options_${i}[]" class="form-control aiz-selectpicker" multiple onchange="update_sku()">`;

                children.forEach(function(e) {
                    div_select += `<option value='{"value": "${e.id}"}'>${e.name}</option>`
                });

                div_select += `
											</select>
										</div>
									</div>`;


                $('#customer_choice_options').append(div_select)

            } else {
                $('#customer_choice_options').append(
                    '<div class="form-group row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' +
                    i + '"><input type="text" class="form-control" name="choice[]" value="' + name +
                    '" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-md-8"><input type="text" class="form-control aiz-tag-input" name="choice_options_' +
                    i +
                    '[]" placeholder="{{ translate('Enter choice values') }}" data-on-change="update_sku"></div></div>'
                );
            }
            AIZ.plugins.tagify();
        }



        $('input[name="alifshop_active"]').on('change', function(e) {
            e.preventDefault();
            if (!$('input[name="alifshop_active"]').is(':checked')) {
                $('#alifshop_category_id').prop('disabled', true);
                $('#alifshop_category_id').next().prop('disabled', true);
                $('#alifshop_category_id').next().addClass('disabled');

                $('#alifshop_brand_id').prop('disabled', true);
                $('#alifshop_brand_id').next().prop('disabled', true);
                $('#alifshop_brand_id').next().addClass('disabled');


            } else {
                $("#alifshop_category_id").prop('disabled', false);
                $('#alifshop_category_id').next().prop('disabled', false);
                $('#alifshop_category_id').next().removeClass('disabled');

                $("#alifshop_brand_id").prop('disabled', false);
                $('#alifshop_brand_id').next().prop('disabled', false);
                $('#alifshop_brand_id').next().removeClass('disabled');
            }
        });


        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors').prop('disabled', true);
            } else {
                $('#colors').prop('disabled', false);
            }
            update_sku();
        });

        $('#colors').on('change', function() {
            update_sku();
        });

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });

        $('input[name="name"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em) {
            $(em).closest('.form-group row').remove();
            update_sku();
        }

        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }

        function update_sku() {
            console.log($('#choice_form').serialize());

            $.ajax({
                type: "POST",
                url: '{{ route('products.sku_combination') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    AIZ.plugins.fooTable();
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {

                var el = $(this);
                add_more_customer_choice_option(el, $(this).val(), $(this).text());
            });
            update_sku();
        });
    </script>

@endsection
