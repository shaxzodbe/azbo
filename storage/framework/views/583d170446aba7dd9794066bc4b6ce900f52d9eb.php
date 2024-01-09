<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-start">
            <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="aiz-user-panel">
                <div class="aiz-titlebar mt-2 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="h3"><?php echo e(translate('Dashboard')); ?></h1>
                        </div>
                    </div>
                </div>
                <div class="row gutters-10">
            		<div class="col-md-6">
            			<div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
            				<div class="px-3 py-4">

                                <?php if(Session::has('cart')): ?>
                                    <div class="h3 fw-700"><?php echo e(count(Session::get('cart'))); ?> <?php echo e(translate('Product(s)')); ?></div>
                                <?php else: ?>
                                    <div class="h3 fw-700">0 <?php echo e(translate('Product')); ?></div>
                                <?php endif; ?>
  					            <div class="opacity-50"><?php echo e(translate('in your cart')); ?></div>
            				</div>
            			</div>
            		</div>
            		<div class="col-md-6">
            			<div class="bg-grad-2 text-white rounded-lg overflow-hidden">
            				<div class="px-3 py-4">
                                <?php
                                    $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                                    $total = 0;
                                    foreach ($orders as $key => $order) {
                                        $total += count($order->orderDetails);
                                    }
                                ?>
              					<div class="h3 fw-700"><?php echo e(count(Auth::user()->wishlists)); ?> <?php echo e(translate('Product(s)')); ?></div>
              					<div class="opacity-50"><?php echo e(translate('in your wishlist')); ?></div>
			                </div>
            			</div>
            		</div>
                </div>
                <div class="row gutters-10">
            		<div class="col-md-6">
            			<div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
            				<div class="px-3 py-3">
                                <?php
                                    $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                                    $total = 0;
                                    foreach ($orders as $key => $order) {
                                        $total += count($order->orderDetails);
                                    }
                                ?>
	        					<div class="h3 fw-700"><?php echo e($total); ?> <?php echo e(translate('Product(s)')); ?></div>
	        					<div class="opacity-50"><?php echo e(translate('you ordered')); ?></div>
				            </div>
            			</div>
            		</div>
            		<div class="col-md-6">
                        <a href="<?php echo e(route('intent_selected_products')); ?>">
                            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                                <div class="px-3 py-3">
                                    <?php
                                        $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                                        $total = 0;
                                        foreach ($orders as $key => $order) {
                                            $total += count($order->orderDetails);
                                        }
                                    ?>
                                    <div class="h3 fw-700"><?php echo e(__('intend.poducts')); ?> (Intend)</div>
                                    <div class="opacity-50"><?php echo e(translate('you ordered')); ?></div>
                                </div>
                            </div>
                        </a>
            		</div>
            	</div>
              <div class="row gutters-10">
            		<div class="col-md-6">
            			<div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><?php echo e(translate('Default Shipping Address')); ?></h6>
                            </div>
    				        <div class="card-body">
                              <?php if(Auth::user()->addresses != null): ?>
                                  <?php
                                      $address = Auth::user()->addresses->where('set_default', 1)->first();
                                  ?>
                                  <?php if($address != null): ?>
                                      <ul class="list-unstyled mb-0">
    					                  <li class=" py-2"><span><?php echo e(translate('Address')); ?> : <?php echo e($address->address); ?></span></li>
                                          <li class=" py-2"><span><?php echo e(translate('Country')); ?> : <?php echo e($address->country); ?></span></li>
                                          <li class=" py-2"><span><?php echo e(translate('City')); ?> : <?php echo e($address->city); ?></span></li>
                                          <li class=" py-2"><span><?php echo e(translate('Postal Code')); ?> : <?php echo e($address->postal_code); ?></span></li>
                                          <li class=" py-2"><span><?php echo e(translate('Phone')); ?> : <?php echo e($address->phone); ?></span></li>
                                      </ul>
                                  <?php endif; ?>
                              <?php endif; ?>
            				</div>
            			</div>
            		</div>
                    <?php if(\App\BusinessSetting::where('type', 'classified_product')->first()->value): ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><?php echo e(translate('Purchased Package')); ?></h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php
                                        $customer_package = \App\CustomerPackage::find(Auth::user()->customer_package_id);
                                    ?>
                                    <?php if($customer_package != null): ?>
                                        <img src="<?php echo e(uploaded_asset($customer_package->logo)); ?>" class="img-fluid mb-4 h-110px">
                                		<p class="mb-1 text-muted"><?php echo e(translate('Product Upload')); ?>: <?php echo e($customer_package->product_upload); ?> <?php echo e(translate('Times')); ?></p>
                                		<p class="text-muted mb-4"><?php echo e(translate('Product Upload Remaining')); ?>: <?php echo e(Auth::user()->remaining_uploads); ?> <?php echo e(translate('Times')); ?></p>
                                        <h5 class="fw-600 mb-3 text-primary"><?php echo e(translate('Current Package')); ?>: <?php echo e($customer_package->getTranslation('name')); ?></h5>
                                    <?php else: ?>
                                        <h5 class="fw-600 mb-3 text-primary"><?php echo e(translate('Package Not Found')); ?></h5>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('customer_packages_list_show')); ?>" class="btn btn-success d-inline-block"><?php echo e(translate('Upgrade Package')); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
            	</div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/customer/dashboard.blade.php ENDPATH**/ ?>