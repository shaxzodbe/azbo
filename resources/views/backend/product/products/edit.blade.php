@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
</div>
<div class="">
	<div class="">
		<form class="form form-horizontal mar-top" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
			<input name="_method" type="hidden" value="POST">
			<input type="hidden" name="id" value="{{ $product->id }}">
	        <input type="hidden" name="lang" value="{{ $lang }}">
			@csrf
			<div class="card">
	            <ul class="nav nav-tabs nav-fill border-light">
	                @foreach (\App\Language::all() as $key => $language)
	                    <li class="nav-item">
	                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('products.admin.edit', ['id'=>$product->id, 'lang'=> $language->code] ) }}">
	                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
	                            <span>{{$language->name}}</span>
	                        </a>
	                    </li>
	                @endforeach
	            </ul>
				<div class="card-body">
					<div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Product Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
	                    <div class="col-lg-8">
	                        <input type="text" class="form-control" name="name" placeholder="{{translate('Product Name')}}" value="{{ $product->getTranslation('name', $lang) }}" required>
	                    </div>
	                </div>
	                <div class="form-group row" id="category">
	                    <label class="col-lg-3 col-form-label">{{translate('Category')}}</label>
	                    <div class="col-lg-8">
	                        <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-selected="{{ $product->category_id }}" data-live-search="true" required>
	                            @foreach ($categories as $category)
	                                <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
	                                @foreach ($category->childrenCategories as $childCategory)
	                                    @include('categories.child_category', ['child_category' => $childCategory])
	                                @endforeach
	                            @endforeach
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group row" id="brand">
	                    <label class="col-lg-3 col-form-label">{{translate('Brand')}}</label>
	                    <div class="col-lg-8">
	                        <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
								<option value="">{{ ('Select Brand') }}</option>
								@foreach (\App\Brand::all() as $brand)
									<option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
								@endforeach
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Unit')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i> </label>
	                    <div class="col-lg-8">
	                        <!-- <input type="text" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" value="{{$product->getTranslation('unit', $lang)}}" required> -->
													<select class="form-control aiz-selectpicker" name="unit" data-live-search="true">
                                   
																	 @foreach (['KG','QTY','SM','L','M','PC'] as $unit)
																			 <option value="{{ $unit }}">{{ $unit }}</option>
																	 @endforeach
															 </select>
											
												</div>
	                </div>
	                <div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Minimum Qty')}}</label>
						<div class="col-lg-8">
							<input type="number" lang="en" class="form-control" name="min_qty" value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif" min="1" required>
						</div>
					</div>
	                <div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Tags')}}</label>
	                    <div class="col-lg-8">
	                        <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags" value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}" data-role="tagsinput" required>
	                    </div>
	                </div>
					@php
					    $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
					@endphp
					@if ($pos_addon != null && $pos_addon->activated == 1)
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">{{translate('Barcode')}}</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
							</div>
						</div>
					@endif

					@php
					    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
					@endphp
					@if ($refund_request_addon != null && $refund_request_addon->activated == 1)
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">{{translate('Refundable')}}</label>
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
					<h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
				</div>
				<div class="card-body">

	                <div class="form-group row">
	                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}}</label>
	                    <div class="col-md-8">
	                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
	                            <div class="input-group-prepend">
	                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
	                            </div>
	                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
	                            <input type="hidden" name="photos" value="{{ $product->photos }}" class="selected-files">
	                        </div>
	                        <div class="file-preview box sm">
	                        </div>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
	                    <div class="col-md-8">
	                        <div class="input-group" data-toggle="aizuploader" data-type="image">
	                            <div class="input-group-prepend">
	                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
	                            </div>
	                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
	                            <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}" class="selected-files">
	                        </div>
	                        <div class="file-preview box sm">
	                        </div>
	                    </div>
	                </div>
					{{-- <div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Gallery Images')}}</label>
						<div class="col-lg-8">
							<div id="photos">
								@if(is_array(json_decode($product->photos)))
									@foreach (json_decode($product->photos) as $key => $photo)
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="img-upload-preview">
												<img loading="lazy"  src="{{ uploaded_asset($photo) }}" alt="" class="img-responsive">
												<input type="hidden" name="previous_photos[]" value="{{ $photo }}">
												<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
											</div>
										</div>
									@endforeach
								@endif
							</div>
						</div>
					</div> --}}
					{{-- <div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
						<div class="col-lg-8">
							<div id="thumbnail_img">
								@if ($product->thumbnail_img != null)
									<div class="col-md-4 col-sm-4 col-xs-6">
										<div class="img-upload-preview">
											<img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
											<input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
											<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
										</div>
									</div>
								@endif
							</div>
						</div>
					</div> --}}
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Video Provider')}}</label>
						<div class="col-lg-8">
							<select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
								<option value="youtube" <?php if($product->video_provider == 'youtube') echo "selected";?> >{{translate('Youtube')}}</option>
								<option value="dailymotion" <?php if($product->video_provider == 'dailymotion') echo "selected";?> >{{translate('Dailymotion')}}</option>
								<option value="vimeo" <?php if($product->video_provider == 'vimeo') echo "selected";?> >{{translate('Vimeo')}}</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Video Link')}}</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<div class="col-lg-3">
							<input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
						</div>
						<div class="col-lg-8">
							<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple>
								@foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
									<option
										value="{{ $color->code }}"
										data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
										<?php if(in_array($color->code, json_decode($product->colors))) echo 'selected'?>
									></option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-1">
	                        <label class="aiz-switch aiz-switch-success mb-0">
	                            <input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($product->colors)) > 0) echo "checked";?> >
	                            <span></span>
	                        </label>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-lg-3">
							<input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
						</div>
								<div class="col-lg-8">
										<select name="choice_attributes[]" id="choice_attributes" data-selected-text-format="count" data-live-search="true" class="form-control aiz-selectpicker" multiple data-placeholder="{{ translate('Choose Attributes') }}">
												@foreach (\App\Attribute::whereNull('parent_id')->get() as $key => $attribute)

													@php
															$children = $attribute->children()->get();
															
															$children_trans = [];
															foreach ($children as $child) {
																array_push($children_trans, [
																	'id' => $child->id, 
																	'name' => $child->getTranslation('name')
																]);
															}
													@endphp
													
													<option value="{{ $attribute->id }}" data-children="{{ json_encode($children_trans) }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->getTranslation('name') }}</option>
												@endforeach
										</select>
								</div>
							</div>

					<div class="">
						<p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
						<br>
					</div>

					<div class="customer_choice_options" id="customer_choice_options">
						@foreach (json_decode($product->choice_options) as $key => $choice_option)
							<div class="form-group row">
								<div class="col-lg-3">
									<input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
									<input type="text" class="form-control" name="choice[]" value="{{ \App\Attribute::find($choice_option->attribute_id)->getTranslation('name') }}" placeholder="{{ translate('Choice Title') }}" disabled>
								</div>
								<div class="col-lg-8">
									{{-- <input type="text" class="form-control aiz-tag-input" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="{{ translate('Enter choice values') }}" value="{{ implode(',', $choice_option->values) }}" data-on-change="update_sku"> --}}
							
									
									@php
										$attrs = \App\Attribute::find($choice_option->attribute_id)->children; 
									@endphp 
								
									<select name="choice_options_{{ $choice_option->attribute_id}}[]" class="form-control aiz-selectpicker" multiple onchange="update_sku()">
										@foreach ($attrs as $item)
											<option value='{"value": "{{ $item->id }}"}' @if(in_array($item->id, $choice_option->values)) selected @endif >{{ $item->getTranslation('name') }}</option>
										@endforeach
									</select>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Unit price')}}</label>
	                    <div class="col-lg-6">
	                        <input type="text" placeholder="{{translate('Unit price')}}" name="unit_price" class="form-control" value="{{$product->unit_price}}" required>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Purchase price')}}</label>
	                    <div class="col-lg-6">
	                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Purchase price')}}" name="purchase_price" class="form-control" value="{{$product->purchase_price}}" required>
	                    </div>
	                </div>
									<!--
	                <div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Tax')}}</label>
	                    <div class="col-lg-6">
	                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('tax')}}" name="tax" class="form-control" value="{{$product->tax}}" required>
	                    </div>
	                    <div class="col-lg-3">
	                        <select class="form-control aiz-selectpicker" name="tax_type" required>
	                        	<option value="amount" <?php if($product->tax_type == 'amount') echo "selected";?> >{{translate('Flat')}}</option>
	                        	<option value="percent" <?php if($product->tax_type == 'percent') echo "selected";?> >{{translate('Percent')}}</option>
	                        </select>
	                    </div>
	                </div>
								-->
	                <div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Discount')}}</label>
	                    <div class="col-lg-6">
	                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" value="{{ $product->discount }}" required>
	                    </div>
	                    <div class="col-lg-3">
	                        <select class="form-control aiz-selectpicker" name="discount_type" required>
	                        	<option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >{{translate('Flat')}}</option>
	                        	<option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >{{translate('Percent')}}</option>
	                        </select>
	                    </div>
	                </div>
					<div class="form-group row" id="quantity">
						<label class="col-lg-3 col-form-label">{{translate('Quantity')}}</label>
						<div class="col-lg-6">
							<input type="number" lang="en" value="{{ $product->current_stock }}" step="1" placeholder="{{translate('Quantity')}}" name="current_stock" class="form-control" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Profit: </label>
						<div class="col-md-6">
							<input type="number" name="profit" id="profit" class="form-control" value="{{ $product->profit }}">
						</div>
					</div>
					<br>
					<div class="sku_combination" id="sku_combination">

					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
	                    <label class="col-lg-3 col-form-label">{{translate('Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
	                    <div class="col-lg-9">
	                        <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
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
										<textarea class="aiz-text-editor" name="characteristics" >{{$product->getTranslation('characteristics')}}</textarea>
								</div>
						</div>
				</div>
			</div>
			{{-- end product characteristics --}}

			
			@if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping')
	            <div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>
					</div>
					<div class="card-body">
	                    <div class="form-group row">
							<div class="col-lg-3">
								<div class="card-heading">
									<h5 class="mb-0 h6">{{translate('Free Shipping')}}</h5>
								</div>
							</div>
							<div class="col-lg-9">
								<div class="form-group row">
									<label class="col-lg-3 col-form-label">{{translate('Status')}}</label>
									<div class="col-lg-8">
										<label class="aiz-switch aiz-switch-success mb-0">
											<input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
											<span></span>
										</label>
									</div>
								</div>
							</div>
						</div>

	                    <div class="form-group row">
							<div class="col-lg-3">
								<div class="card-heading">
									<h5 class="mb-0 h6">{{translate('Flat Rate')}}</h5>
								</div>
							</div>
							<div class="col-lg-9">
								<div class="form-group row">
									<label class="col-lg-3 col-form-label">{{translate('Status')}}</label>
									<div class="col-lg-8">
										<label class="aiz-switch aiz-switch-success mb-0">
											<input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
											<span></span>
										</label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-form-label">{{translate('Shipping cost')}}</label>
									<div class="col-lg-8">
										<input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
	        @endif
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
				</div>
				<div class="card-body">
	                <div class="form-group row">
	                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>
	                    <div class="col-md-8">
	                        <div class="input-group" data-toggle="aizuploader">
	                            <div class="input-group-prepend">
	                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
	                            </div>
	                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
	                            <input type="hidden" name="pdf" value="{{ $product->pdf }}" class="selected-files">
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
								<select class="form-control selectpicker" data-live-search="true" title="Nothing selected" id="alifshop_category_id"
									name="alifshop_category_id" <?php if(!$product->alifshop) echo "disabled"; ?>>
									@forelse (\App\AlifshopCategory::where('is_active',true)->get() as $cat)
									<option value="{{ $cat->id }}" <?php if($product->alifshop_category_id == $cat->id) echo "selected"; ?> >{{ $cat->name }} </option>
									@empty
									@endforelse
								</select>
							</div>
							@if(!$product->alifshop)
							<div class="col-md-1">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input value="1" type="checkbox" name="alifshop_active" <?php if($product->alifshop) echo "checked"; ?>>
									<span></span>
								</label>
							</div>
							@endif
						</div>
	
						<div class="form-group row">
							<label class="col-md-3 col-form-label" for="signinSrEmail">Brand : </label>
							<div class="col-md-8">
								<select class="form-control selectpicker" data-live-search="true" title="Nothing selected" id="alifshop_brand_id"
									name="alifshop_brand_id" <?php if(!$product->alifshop) echo "disabled"; ?> >
									@forelse (\App\AlifshopBrand::all() as $brand)
									<option value="{{ $brand->id }}" <?php if($product->alifshop_brand_id == $brand->id) echo "selected"; ?>>{{ $brand->name }} </option>
									@empty
									@endforelse
								</select>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Visible:</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="checkbox" name="alifshop_visible" <?php if($product->alifshop_visible) echo "checked"; ?>>
									<span></span>
								</label>
							</div>
						</div>
	
					</div>
				</div>
				<!-- end alifshop -->

			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Meta Title')}}</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{translate('Meta Title')}}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">{{translate('Description')}}</label>
						<div class="col-lg-8">
							<textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
						</div>
					</div>
	                <div class="form-group row">
	                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Meta Images')}}</label>
	                    <div class="col-md-8">
	                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
	                            <div class="input-group-prepend">
	                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
	                            </div>
	                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
	                            <input type="hidden" name="meta_img" value="{{ $product->meta_img }}" class="selected-files">
	                        </div>
	                        <div class="file-preview box sm">
	                        </div>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
	                    <div class="col-md-8">
	                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $product->slug }}" class="form-control">
	                    </div>
	                </div>
				</div>
			</div>

			@if(\App\BusinessSetting::where('type', 'bonus_product_activation')->first()->value)
			<!-- Bonus product  section -->
			<div class="card">
				<div class="card-header">
						<h5 class="mb-0 h6">{{ translate('Bonus product') }}</h5>
				</div>
				<div class="card-body">
						<div class="form-group row">
								<label class="col-md-3 col-form-label">{{ translate('Product name') }}: </label>
								<div class="col-md-8">
										<select name="bonus_product_id" id="" class="form-control  aiz-selectpicker" data-live-search="true">
												<option selected disabled>Select from list </option>
												@forelse (\App\Product::where('bonus', true)->get() as $p)
														<option value="{{$p->id}}" @if($product->bonus_product_id === $p->id) selected @endif>{{ $p->getTranslation('name') }}</option>
												@empty
												@endforelse
										</select>
								</div>
						</div>
						<div class="form-group row">
							<label for="bonus_any_quantity" class="col-md-3 col-form-label">{{ translate('One bonus') }} ?:</label>
							<div class="col-lg-8">
								<label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
									<input type="checkbox" name="bonus_any_quantity" @if ($product->bonus_any_quantity) checked @endif>
		                            <span class="slider round"></span></label>
								</label>
							</div>

						</div>

						<div class="form-group row">
								<label class="col-md-3 col-form-label">translate('Condition'):</label>
								<div class="col-md-8">
										<select name="bonus_condition" id="" class="form-control">
											@foreach(range(1,9) as $n)
												<option value="{{$n}}" @if($n === $product->bonus_condition) selected @endif>{{$n}}</option>
											@endforeach
										</select>
								</div>
						</div>
				</div>
		</div>
		@endif
		<!-- end Bonus product section -->


			<div class="mb-3 text-right">
				<button type="submit" name="button" class="btn btn-info">{{ translate('Update Product') }}</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')

<script type="text/javascript">

	function add_more_customer_choice_option(el, i, name){
		
		var d = el.attr('data-children');
		var children = JSON.parse(d);

		
		var div_select = `
				<div class="form-group row"> 
					<div class="col-md-3">
						<input type="hidden" name="choice_no[]" value="${i}">
						<input type="text" class="form-control" name="choice[]" value="${name}" placeholder="{{ translate('Choice Title') }}" readonly>
					</div>
					<div class="col-md-8">
						<select name="choice_options_${i}[]" class="form-control aiz-selectpicker" multiple onchange="update_sku()">`;

			children.forEach(function (e) {
						div_select += `<option value='{"value": "${e.id}"}'>${e.name}</option>`
			});

			div_select += `
						</select>
					</div>
				</div>`;

			$('#customer_choice_options').append(div_select)


      // $('#customer_choice_options').append('<div class="form-group row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-md-8"><input type="text" class="form-control aiz-tag-input" name="choice_options_'+i+'[]" placeholder="{{ translate('Enter choice values') }}" data-on-change="update_sku"></div></div>');
    	// AIZ.plugins.tagify();
	}

  $('input[name="alifshop_active"]').on('change', function(e) {
		e.preventDefault();
		if(!$('input[name="alifshop_active"]').is(':checked')) {
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
	    if(!$('input[name="colors_active"]').is(':checked')){
			$('#colors').prop('disabled', true);
		}
		else{
			$('#colors').prop('disabled', false);
		}
		update_sku();
	});

	$('#colors').on('change', function() {
	    update_sku();
	});

	function delete_row(em){
		$(em).closest('.form-group').remove();
		update_sku();
	}

    function delete_variant(em){
		$(em).closest('.variant').remove();
	}

	function update_sku(){
		$.ajax({
		   type:"POST",
		   url:'{{ route('products.sku_combination_edit') }}',
		   data:$('#choice_form').serialize(),
		   success: function(data){
			    $('#sku_combination').html(data);
          AIZ.uploader.previewGenerate();
    			AIZ.plugins.fooTable();
			   if (data.length > 1) {
				   $('#quantity').hide();
			   }
			   else {
					$('#quantity').show();
			   }
		   }
	   });
	}

    AIZ.plugins.tagify();

	$(document).ready(function(){
		update_sku();

		$('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
	});

	$('#choice_attributes').on('change', function() {
		$.each($("#choice_attributes option:selected"), function(j, attribute){
			
			flag = false;
			$('input[name="choice_no[]"]').each(function(i, choice_no) {
				if($(attribute).val() == $(choice_no).val()){
					flag = true;
				}
			});
			if(!flag){
				var el = $(this)
				add_more_customer_choice_option(el, $(attribute).val(), $(attribute).text());
			}
    });

		var str = @php echo $product->attributes @endphp;


		$.each(str, function(index, value){
			flag = false;
			$.each($("#choice_attributes option:selected"), function(j, attribute){
				if(value == $(attribute).val()){
					flag = true;
				}
			});
      if(!flag){
				$('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
			}
		});

		update_sku();
	});

</script>

@endsection
