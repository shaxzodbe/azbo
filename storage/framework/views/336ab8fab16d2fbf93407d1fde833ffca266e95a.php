

<?php $__env->startSection('content'); ?>
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4"><?php echo e(translate('Track Order')); ?></h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="<?php echo e(route('home')); ?>"><?php echo e(translate('Home')); ?></a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="<?php echo e(route('orders.track')); ?>">"<?php echo e(translate('Track Order')); ?>"</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="mb-5">
    <div class="container text-left">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-lg-8 mx-auto">
                <form class="" action="<?php echo e(route('orders.track')); ?>" method="GET" enctype="multipart/form-data">
                    <div class="bg-white rounded shadow-sm">
                        <div class="fs-15 fw-600 p-3 border-bottom text-center">
                            <?php echo e(translate('Check Your Order Status')); ?>

                        </div>
                        <div class="form-box-content p-3">
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" placeholder="<?php echo e(translate('Order Code')); ?>" name="order_code" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><?php echo e(translate('Track Order')); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(isset($order)): ?>
            <div class="bg-white rounded shadow-sm mt-5">
                <div class="fs-15 fw-600 p-3 border-bottom">
                    <?php echo e(translate('Order Summary')); ?>

                </div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Order Code')); ?>:</td>
                                    <td><?php echo e($order->code); ?></td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Customer')); ?>:</td>
                                    <td><?php echo e(json_decode($order->shipping_address)->name); ?></td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Email')); ?>:</td>
                                    <?php if($order->user_id != null): ?>
                                        <td><?php echo e($order->user->email); ?></td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Shipping address')); ?>:</td>
                                    <td><?php echo e(json_decode($order->shipping_address)->address); ?>, <?php echo e(json_decode($order->shipping_address)->city); ?>, <?php echo e(json_decode($order->shipping_address)->country); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Order date')); ?>:</td>
                                    <td><?php echo e(date('d-m-Y H:i A', $order->date)); ?></td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Total order amount')); ?>:</td>
                                    <td><?php echo e(single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax'))); ?></td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Shipping method')); ?>:</td>
                                    <td><?php echo e(translate('Flat shipping rate')); ?></td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Payment method')); ?>:</td>
                                    <td><?php echo e(ucfirst(str_replace('_', ' ', $order->payment_type))); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $status = $orderDetail->delivery_status;
                ?>
                <div class="bg-white rounded shadow-sm mt-4">
                    <div class="p-4">
                        <ul class="list-inline text-center aiz-steps">
                            <li class="list-inline-item <?php if($status == 'pending'): ?> active <?php else: ?> done <?php endif; ?>">
                                <div class="icon">
                                    <i class="las la-file-invoice"></i>
                                </div>
                                <div class="title"><?php echo e(translate('Order placed')); ?></div>
                            </li>
                            <li class="list-inline-item <?php if($status == 'confirmed'): ?> active <?php elseif($status == 'on_delivery' || $status == 'delivered'): ?> done <?php endif; ?>">
                                <div class="icon">
                                    <i class="las la-newspaper"></i>
                                </div>
                                <div class="title"><?php echo e(translate('Confirmed')); ?></div>
                            </li>
                            <li class="list-inline-item <?php if($status == 'on_delivery'): ?> active <?php elseif($status == 'delivered'): ?> done <?php endif; ?>">
                                <div class="icon">
                                    <i class="las la-truck"></i>
                                </div>
                                <div class="title"><?php echo e(translate('On delivery')); ?></div>
                            </li>
                            <li class="list-inline-item <?php if($status == 'delivered'): ?> done <?php endif; ?>">
                                <div class="icon">
                                    <i class="las la-clipboard-check"></i>
                                </div>
                                <div class="title"><?php echo e(translate('Delivered')); ?></div>
                            </li>
                        </ul>
                    </div>
                    <?php if($orderDetail->product != null): ?>
                    <div class="p-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(translate('Product Name')); ?></th>
                                    <th><?php echo e(translate('Quantity')); ?></th>
                                    <th><?php echo e(translate('Shipped By')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td><?php echo e($orderDetail->product->getTranslation('name')); ?> (<?php echo e($orderDetail->variation); ?>)</td>
                                    <td><?php echo e($orderDetail->quantity); ?></td>
                                    <td><?php echo e($orderDetail->product->user->name); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/track_order.blade.php ENDPATH**/ ?>