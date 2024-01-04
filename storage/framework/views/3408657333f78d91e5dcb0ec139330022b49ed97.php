    <div class="container">
        <?php if(Session::has('cart') && count(Session::get('cart')) > 0): ?>
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card">
                        <div class="card-header bg-soft-primary">
                            <h3 class="fs-16 fw-600 mb-0"><?php echo e(translate('1. My Cart')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="shadow-sm bg-white mb-3   text-left">
                                <ul class="list-group list-group-flush">
                                    <?php
                                        $total = 0;
                                        $subtotal = 0;
                                        $tax = 0;
                                        $shipping = 0;
                                    ?>
                                    <?php $__currentLoopData = Session::get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php

                                            $product = \App\Product::find($cartItem['id']);
                                            $total = $total + $cartItem['price'] * $cartItem['quantity'];
                                            $subtotal += $cartItem['price']*$cartItem['quantity'];
                                            $tax += $cartItem['tax']*$cartItem['quantity'];
                                            $shipping += $cartItem['shipping'];
                                            $product_name_with_choice = $product->getTranslation('name');
                                            if ($cartItem['variant'] != null) {
                                                $product_name_with_choice = $product->getTranslation('name') .'-'. get_variant_string($cartItem['variant']);
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

                                                    <span
                                                        class="fs-14 opacity-60"><?php echo e($product_name_with_choice); ?></span>
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
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-header bg-soft-primary">
                            <h3 class="fs-16 fw-600 mb-0"><?php echo e(translate('Items')); ?> soni : <?php echo e(count(Session::get('cart'))); ?></h3>
                        </div>

                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                <tr class="cart-shipping">
                                    <th style="border-top: none;"><?php echo e(translate('Tax')); ?></th>
                                    <td class="text-right" style="border-top: none;">
                                        <span class="font-italic"><?php echo e(single_price($tax)); ?></span>
                                    </td>
                                </tr>

                                <tr class="cart-shipping">
                                    <th><?php echo e(translate('Total Shipping')); ?></th>
                                    <td class="text-right">
                                        <span class="font-italic">20000</span>
                                    </td>
                                </tr>
                                <tr class="cart-shipping">
                                    <th><?php echo e(__('Jami Buyutmalar')); ?></th>
                                    <td class="text-right">
                                        <span class="font-italic"><?php echo e(single_price($total)); ?></span>
                                    </td>
                                </tr>
                                <?php if(Session::has('coupon_discount')): ?>
                                    <tr class="cart-shipping">
                                        <th><?php echo e(translate('Coupon Discount')); ?></th>
                                        <td class="text-right">
                                                    <span
                                                        class="font-italic"><?php echo e(single_price(Session::get('coupon_discount'))); ?></span>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php
                                    $total = $subtotal+$tax+$shipping;
                                    if(Session::has('coupon_discount')){
                                        $total -= Session::get('coupon_discount');
                                    }
                                ?>

                                <tr class="cart-total">
                                    <th><span class="strong-600"><?php echo e(translate('Total')); ?></span></th>
                                    <td class="text-right">
                                        <strong><span><?php echo e(single_price($total+20000)); ?></span></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            
                            <a href="<?php echo e(route('checkout.detail_payment')); ?>"
                               class="btn btn-primary btn-block fw-600">Buyurtmani rasmiylashtirish</a>
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
<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
<?php /**PATH /var/www/azbo/resources/views/frontend/partials/my_cart_details.blade.php ENDPATH**/ ?>