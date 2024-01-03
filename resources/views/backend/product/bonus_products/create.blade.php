@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<h5 class="mb-0 h6">Add New Bonus Product</h5>
</div>
<div class="">
	<div class="">
		<form class="form form-horizontal mar-top" action="{{route('bonus_products.store')}}" method="POST"
			enctype="multipart/form-data" id="choice_form">
			@csrf
			<input type="hidden" name="added_by" value="admin">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Product Name')}} <span
								class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="name" placeholder="{{ translate('Product Name') }}"
								onchange="update_sku()" required>
						</div>
					</div>
					<div class="form-group row" id="category">
						<label class="col-md-3 col-from-label">{{translate('Category')}} <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true"
								required>
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
						<label class="col-md-3 col-from-label">{{translate('Brand')}}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
								<option value="">{{ ('Select Brand') }}</option>
								@foreach (\App\Brand::all() as $brand)
								<option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Unit')}}</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="unit"
								placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Minimum Qty')}} <span
								class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control aiz-tag-input" name="tags[]"
								placeholder="{{ translate('Type and hit enter to add a tag') }}" required>
							<small
								class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>
						</div>
					</div>

					@php
					$pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
					@endphp
					@if ($pos_addon != null && $pos_addon->activated == 1)
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Barcode')}}</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}">
						</div>
					</div>
					@endif

					@php
					$refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
					@endphp
					@if ($refund_request_addon != null && $refund_request_addon->activated == 1)
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Refundable')}}</label>
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
					<h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}}
							<small>(600x600)</small></label>
						<div class="col-md-8">
							<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
								<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
								</div>
								<div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="photos" class="selected-files">
							</div>
							<div class="file-preview box sm">
							</div>
							<small
								class="text-muted">{{translate('These images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}}
							<small>(300x300)</small></label>
						<div class="col-md-8">
							<div class="input-group" data-toggle="aizuploader" data-type="image">
								<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
								</div>
								<div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="thumbnail_img" class="selected-files">
							</div>
							<div class="file-preview box sm">
							</div>
							<small
								class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
						</div>
					</div>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Unit price')}} <span
								class="text-danger">*</span></label>
						<div class="col-md-6">
							<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Unit price') }}"
								name="unit_price" class="form-control" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Purchase price')}} <span
								class="text-danger">*</span></label>
						<div class="col-md-6">
							<input type="number" lang="en" min="0" value="0" step="0.01"
								placeholder="{{ translate('Purchase price') }}" name="purchase_price" class="form-control" required>
						</div>
					</div>


					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Discount')}} <span class="text-danger">*</span></label>
						<div class="col-md-6">
							<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Discount') }}"
								name="discount" class="form-control" required>
						</div>
						<div class="col-md-3">
							<select class="form-control aiz-selectpicker" name="discount_type">
								<option value="amount">{{translate('Flat')}}</option>
								<option value="percent">{{translate('Percent')}}</option>
							</select>
						</div>
					</div>
					<div class="form-group row" id="quantity">
						<label class="col-md-3 col-from-label">{{translate('Quantity')}} <span class="text-danger">*</span></label>
						<div class="col-md-6">
							<input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('Quantity') }}"
								name="current_stock" class="form-control" required>
						</div>
					</div>
				</div>
			</div>

		

			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Description')}}</label>
						<div class="col-md-8">
							<textarea class="aiz-text-editor" name="description"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Description')}}</label>
						<div class="col-md-8">
							<textarea name="meta_description" rows="8" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
						<div class="col-md-8">
							<div class="input-group" data-toggle="aizuploader" data-type="image">
								<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
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

			<div class="mb-3 text-right">
				<button type="submit" name="button" class="btn btn-primary">{{ translate('Save Product') }}</button>
			</div>
		</form>
	</div>
</div>



@endsection
