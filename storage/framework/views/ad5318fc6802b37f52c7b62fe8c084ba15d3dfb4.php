

<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-auto">
			<h1 class="h3"><?php echo e(translate('All products')); ?></h1>
		</div>
        <?php if($type != 'Seller'): ?>
    		<div class="col text-right">
    			<a href="<?php echo e(route('products.create')); ?>" class="btn btn-circle btn-info">
    				<span><?php echo e(translate('Add New Product')); ?></span>
    			</a>
    		</div>
        <?php endif; ?>
	</div>
</div>
<br>

<div class="card">
	<form class="" id="sort_products" action="" method="GET">
		<div class="card-header row gutters-5">
			<div class="col text-center text-md-left">
				<h5 class="mb-md-0 h6"><?php echo e(translate('All Product')); ?></h5>
			</div>
			<?php if($type == 'Seller'): ?>
			<div class="col-md-2 ml-auto">
				<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
					<option value=""><?php echo e(translate('All Sellers')); ?></option>
		            <?php $__currentLoopData = App\Seller::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		                <?php if($seller->user != null && $seller->user->shop != null): ?>
		                    <option value="<?php echo e($seller->user->id); ?>" <?php if($seller->user->id == $seller_id): ?> selected <?php endif; ?>><?php echo e($seller->user->shop->name); ?> (<?php echo e($seller->user->name); ?>)</option>
		                <?php endif; ?>
		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<?php endif; ?>
			<?php if($type == 'All'): ?>
			<div class="col-md-2 ml-auto">
				<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
					<option value=""><?php echo e(translate('All Sellers')); ?></option>
		            <?php $__currentLoopData = App\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                		<option value="<?php echo e($seller->id); ?>" <?php if($seller->id == $seller_id): ?> selected <?php endif; ?>><?php echo e($seller->name); ?></option>
		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<?php endif; ?>
			<div class="col-md-2 ml-auto">
				<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type" onchange="sort_products()">
					<option value=""><?php echo e(translate('Sort By')); ?></option>
			        <option value="rating,desc" <?php if(isset($col_name , $query)): ?> <?php if($col_name == 'rating' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Rating (High > Low)')); ?></option>
			        <option value="rating,asc" <?php if(isset($col_name , $query)): ?> <?php if($col_name == 'rating' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Rating (Low > High)')); ?></option>
			        <option value="num_of_sale,desc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'num_of_sale' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Num of Sale (High > Low)')); ?></option>
			        <option value="num_of_sale,asc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'num_of_sale' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Num of Sale (Low > High)')); ?></option>
			        <option value="unit_price,desc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'unit_price' && $query == 'desc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Base Price (High > Low)')); ?></option>
			        <option value="unit_price,asc"<?php if(isset($col_name , $query)): ?> <?php if($col_name == 'unit_price' && $query == 'asc'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Base Price (Low > High)')); ?></option>
				</select>
			</div>
			<div class="col-md-2">
				<div class="form-group mb-0">
					<input type="text" class="form-control form-control-sm" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type & Enter')); ?>">
				</div>
			</div>
		</div>
	</from>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th width="30%"><?php echo e(translate('Name')); ?></th>
                    <?php if($type == 'Seller' || $type == 'All'): ?>
                        <th data-breakpoints="lg"><?php echo e(translate('Added By')); ?></th>
                    <?php endif; ?>
                    <th data-breakpoints="lg"><?php echo e(translate('Num of Sale')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Total Stock')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Base Price')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Todays Deal')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Rating')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Published')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Featured')); ?></th>
                    <th data-breakpoints="lg">Alifshop</th>
                    <th data-breakpoints="lg" class="text-right"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($key+1) + ($products->currentPage() - 1)*$products->perPage()); ?></td>
                        <td>
							<div class="row gutters-5">
								<div class="col-auto">
									<img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" alt="Image" class="size-50px img-fit">
								</div>
								<div class="col">
									<span class="text-muted text-truncate-2"><?php echo e($product->getTranslation('name')); ?></span>
								</div>
							</div>
                        </td>
                        <?php if($type == 'Seller' || $type == 'All'): ?>
                            <td><?php echo e($product->user->name); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($product->num_of_sale); ?> <?php echo e(translate('times')); ?></td>
                        <td>
                            <?php
                                $qty = 0;
                                if($product->variant_product){
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                    }
                                }
                                else{
                                    $qty = $product->current_stock;
                                }
                                echo $qty;
                            ?>
                        </td>
                        <td><?php echo e(number_format($product->unit_price,0, ' ', ' ')); ?></td>
                        <td>
							<label class="aiz-switch aiz-switch-success mb-0">
                              <input onchange="update_todays_deal(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->todays_deal == 1) echo "checked";?> >
                              <span class="slider round"></span>
							</label>
						</td>
                        <td><?php echo e($product->rating); ?></td>
                        <td>
							<label class="aiz-switch aiz-switch-success mb-0">
                              <input onchange="update_published(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                              <span class="slider round"></span>
							</label>
						</td>
                      	<td>
							<label class="aiz-switch aiz-switch-success mb-0">
	                            <input onchange="update_featured(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->featured == 1) echo "checked";?> >
	                            <span class="slider round"></span>
							</label>
						</td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" value=<?php echo e($product->id); ?> <?php if($product->alifshop) echo "checked"; ?> disabled>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        
						<td class="text-right">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="<?php echo e(route('product', $product->slug)); ?>" target="_blank" title="<?php echo e(translate('View')); ?>">
                               <i class="las la-eye"></i>
                           </a>
							<?php if($type == 'Seller'): ?>
    	                      <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('products.seller.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>" title="<?php echo e(translate('Edit')); ?>">
    	                          <i class="las la-edit"></i>
    	                      </a>
							<?php else: ?>
								<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>" title="<?php echo e(translate('Edit')); ?>">
								   <i class="las la-edit"></i>
							   </a>
							<?php endif; ?>
							<a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="<?php echo e(route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )); ?>" title="<?php echo e(translate('Duplicate')); ?>">
							   <i class="las la-copy"></i>
						   </a>
                           <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('products.destroy', $product->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                              <i class="las la-trash"></i>
                           </a>
                      </td>
                  	</tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($products->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.todays_deal')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Todays Deal updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.published')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Published products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.featured')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Featured products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function sort_products(el){
            $('#sort_products').submit();
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/product/products/index.blade.php ENDPATH**/ ?>