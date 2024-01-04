

<?php $__env->startSection('content'); ?>

    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col active">
                            <div class="text-center text-primary">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize"><?php echo e(translate('1. My Cart')); ?>

                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 text-capitalize">
                                    <?php echo e(translate('2. Shipping info')); ?></h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 text-capitalize">
                                    <?php echo e(translate('3. Delivery info')); ?></h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 text-capitalize">
                                    <?php echo e(translate('4. Payment')); ?></h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 text-capitalize">
                                    <?php echo e(translate('5. Confirmation')); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4" id="cart-summary">
        <div class="container">
            <?php if(Session::has('cart') && count(Session::get('cart')) > 0): ?>
                <div class="row">
                    <div class="col-xxl-8 col-xl-10 mx-auto">
                        <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                            <div class="mb-4">
                                <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
                                    <div class="col-md-5 fw-600"><?php echo e(translate('Product')); ?></div>
                                    <div class="col fw-600"><?php echo e(translate('Price')); ?></div>
                                    
                                    <div class="col fw-600"><?php echo e(translate('Quantity')); ?></div>
                                    <div class="col fw-600"><?php echo e(translate('Total')); ?></div>
                                    <div class="col-auto fw-600"><?php echo e(translate('Remove')); ?></div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php
                                        $total = 0;
                                    ?>
                                    <?php $__currentLoopData = Session::get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $product = \App\Product::find($cartItem['id']);


                                            $total = $total + $cartItem['price'] * $cartItem['quantity'];
                                            $product_name_with_choice = $product->getTranslation('name');
                                            if ($cartItem['variant'] != null) {
                                                
                                                $product_name_with_choice = $product->getTranslation('name') .'-'. get_variant_string($cartItem['variant']);
                                                // $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                                            }

                                            if($cartItem['bonus']) {
                                                $product_name_with_choice .= ' (' . translate('bonus') . ')';
                                            }
                                        ?>
                                      
                                        <li class="list-group-item px-0 px-lg-3">
                                            <div class="row gutters-5">
                                                <div class="col-lg-5 d-flex">
                                                    <span class="mr-2 ml-0">
                                                        <img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>"
                                                            class="img-fit size-60px rounded"
                                                            alt="<?php echo e($product->getTranslation('name')); ?>">
                                                    </span>

                                                    <span class="fs-14 opacity-60"><?php echo e($product_name_with_choice); ?></span>
                                                </div>

                                                <div class="col-lg col-4 order-1 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none"><?php echo e(translate('Price')); ?></span>
                                                    <span
                                                        class="fw-600 fs-16"><?php echo e(single_price($cartItem['price'])); ?></span>
                                                </div>
                                                

                                                <div class="col-lg col-6 order-4 order-lg-0">
                                                    <?php if($cartItem['digital'] != 1): ?>
                                                        <div
                                                            class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0">
                                                            <?php if(!$cartItem['bonus']): ?>
                                                            <button
                                                                class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                type="button" data-type="minus"
                                                                data-field="quantity[<?php echo e($key); ?>]">
                                                                <i class="las la-minus"></i>
                                                            </button>
                                                            <?php endif; ?>
                                                            <input type="text" name="quantity[<?php echo e($key); ?>]"
                                                                class="col border-0 text-center flex-grow-1 fs-16 input-number"
                                                                placeholder="1" value="<?php echo e($cartItem['quantity']); ?>"
                                                                min="1" max="10" readonly
                                                                onchange="updateQuantity(<?php echo e($key); ?>, this)">
                                                            <?php if(!$cartItem['bonus']): ?>
                                                            <button
                                                                class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                type="button" data-type="plus"
                                                                data-field="quantity[<?php echo e($key); ?>]">
                                                                <i class="las la-plus"></i>
                                                            </button>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-lg col-4 order-3 order-lg-0 my-3 my-lg-0">
                                                    <span
                                                        class="opacity-60 fs-12 d-block d-lg-none"><?php echo e(translate('Total')); ?></span>
                                                    <span
                                                        class="fw-600 fs-16 text-primary"><?php echo e(single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'])); ?></span>
                                                </div>
                                                <div class="col-lg-auto col-6 order-5 order-lg-0 text-right">
                                                    <a href="javascript:void(0)"
                                                        onclick="removeFromCartView(event, <?php echo e($key); ?>)"
                                                        class="btn btn-icon btn-sm btn-soft-primary btn-circle">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>

                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            <div class="px-3 py-2 mb-4 border-top d-flex justify-content-between">
                                <span class="opacity-60 fs-15"><?php echo e(translate('Subtotal')); ?></span>
                                <span class="fw-600 fs-17"><?php echo e(single_price($total)); ?></span>
                            </div>


                            <div class="row align-items-center">
                                <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-link">
                                        <i class="las la-arrow-left"></i>
                                        <?php echo e(translate('Return to shop')); ?>

                                    </a>
                                </div>
                                <div class="col-md-6 text-center text-md-right">
                                    <?php if(Auth::check()): ?>
                                        <a href="<?php echo e(route('checkout.shipping_info')); ?>"
                                            class="btn btn-primary fw-600"><?php echo e(translate('Continue to Shipping')); ?></a>
                                    <?php else: ?>
                                        <button class="btn btn-primary fw-600"
                                            onclick="showCheckoutModal()"><?php echo e(translate('Continue to Shipping')); ?></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-xl-8 mx-auto">
                        <div class="shadow-sm bg-white p-4 rounded">
                            <div class="text-center p-3">
                                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                <h3 class="h4 fw-700"><?php echo e(translate('Your Cart is empty')); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <div class="modal fade" id="GuestCheckout">
        <div class="modal-dialog modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600"><?php echo e(translate('Login')); ?></h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="<?php echo e(route('cart.login.submit')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <?php if(\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated): ?>
                                    <input type="text"
                                        class="form-control h-auto form-control-lg <?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"
                                        value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(translate('Email Or Phone')); ?>"
                                        name="email" id="email">
                                <?php else: ?>
                                    <input type="email"
                                        class="form-control h-auto form-control-lg <?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"
                                        value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(translate('Email')); ?>"
                                        name="email">
                                <?php endif; ?>
                                <?php if(\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated): ?>
                                    <span class="opacity-60"><?php echo e(translate('Use country code before number')); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                    placeholder="<?php echo e(translate('Password')); ?>">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <span class=opacity-60><?php echo e(translate('Remember Me')); ?></span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="<?php echo e(route('password.request')); ?>"
                                        class="text-reset opacity-60 fs-14"><?php echo e(translate('Forgot password?')); ?></a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit"
                                    class="btn btn-primary btn-block fw-600"><?php echo e(translate('Login')); ?></button>
                            </div>
                        </form>

                    </div>
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0"><?php echo e(translate('Dont have an account?')); ?></p>
                        <a href="<?php echo e(route('user.registration')); ?>"><?php echo e(translate('Register Now')); ?></a>
                    </div>
                    <?php if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1): ?>
                        <div class="separator mb-3">
                            <span class="bg-white px-3 opacity-60"><?php echo e(translate('Or Login With')); ?></span>
                        </div>
                        <ul class="list-inline social colored text-center mb-3">
                            <?php if(\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1): ?>
                                <li class="list-inline-item">
                                    <a href="<?php echo e(route('social.login', ['provider' => 'facebook'])); ?>" class="facebook">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1): ?>
                                <li class="list-inline-item">
                                    <a href="<?php echo e(route('social.login', ['provider' => 'google'])); ?>" class="google">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1): ?>
                                <li class="list-inline-item">
                                    <a href="<?php echo e(route('social.login', ['provider' => 'twitter'])); ?>" class="twitter">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if(\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1): ?>
                        <!-- <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60"><?php echo e(translate('Or')); ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo e(route('checkout.shipping_info')); ?>" class="btn btn-soft-primary"><?php echo e(translate('Guest Checkout')); ?></a>
                            </div> -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                key: key,
                quantity: element.value
            }, function(data) {
                updateNavCart();
                $('#cart-summary').html(data);
            });
        }

        function showCheckoutModal() {
            $('#GuestCheckout').modal();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/view_cart.blade.php ENDPATH**/ ?>