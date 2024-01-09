

<?php $__env->startSection('content'); ?>

<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-start">
            <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="aiz-user-panel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Purchase History')); ?></h5>
                    </div>
                    <?php if(count($applications) > 0): ?>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                    <tr>
                                        <th><?php echo e(translate('Code')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Date')); ?></th>
                                        <th><?php echo e(translate('Amount')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Delivery Status')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Payment Status')); ?></th>
                                        <th class="text-right"><?php echo e(translate('Options')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                        <tr>
                                            <td><?php echo e($application->code); ?></td>
                                            <td><?php echo e($application->created_at); ?> </td>
                                            <td><?php echo e(single_price($application->price)); ?> </td>
                                            <td>-</td>
                                            <td>
                                                <?php if($application->status == 'approved'): ?>
                                                    <span class="badge badge-inline badge-success"><?php echo e(translate($application->status)); ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-inline badge-danger"><?php echo e(translate($application->status)); ?></span>
                                                <?php endif; ?>
                                                  
                                            </td>
                                            <td>
                                                -
                                            </td>
                                            
                                        </tr>   

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 



                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($order->orderDetails) > 0): ?>
                                            <tr>
                                                <td>
                                                    <a href="#<?php echo e($order->code); ?>" onclick="show_purchase_history_details(<?php echo e($order->id); ?>)"><?php echo e($order->code); ?></a>
                                                </td>
                                                <td><?php echo e(date('d-m-Y', $order->date)); ?></td>
                                                    <td>
                                                        <?php echo e(single_price($order->grand_total)); ?>

                                                    </td>
                                                <td>
                                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->orderDetails->first()->delivery_status)))); ?>

                                                    <?php if($order->delivery_viewed == 0): ?>
                                                        <span class="ml-2" style="color:green"><strong>*</strong></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($order->payment_status == 'paid'): ?>
                                                        <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                                                    <?php endif; ?>
                                                    <?php if($order->payment_status_viewed == 0): ?>
                                                        <span class="ml-2" style="color:green"><strong>*</strong></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if($order->orderDetails->first()->delivery_status == 'pending' && $order->payment_status == 'unpaid'): ?>
                                                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('orders.destroy', $order->id)); ?>" title="<?php echo e(translate('Cancel')); ?>">
                                                           <i class="las la-trash"></i>
                                                       </a>
                                                    <?php endif; ?>
                                                    <a href="javascript:void(0)" class="btn btn-soft-info btn-icon btn-circle btn-sm" onclick="show_purchase_history_details(<?php echo e($order->id); ?>)" title="<?php echo e(translate('Order Details')); ?>">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                    <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="<?php echo e(route('invoice.download', $order->id)); ?>" title="<?php echo e(translate('Download Invoice')); ?>">
                                                        <i class="las la-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                	<?php echo e($orders->links()); ?>

                          	</div>
                        </div>
                    <?php endif; ?>



                    <?php if(count($orders) > 0): ?>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                    <tr>
                                        <th><?php echo e(translate('Code')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Date')); ?></th>
                                        <th><?php echo e(translate('Amount')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Delivery Status')); ?></th>
                                        <th data-breakpoints="md"><?php echo e(translate('Payment Status')); ?></th>
                                        <th class="text-right"><?php echo e(translate('Options')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($order->orderDetails) > 0): ?>
                                            <tr>
                                                <td>
                                                    <a href="#<?php echo e($order->code); ?>" onclick="show_purchase_history_details(<?php echo e($order->id); ?>)"><?php echo e($order->code); ?></a>
                                                </td>
                                                <td><?php echo e(date('d-m-Y', $order->date)); ?></td>
                                                <td>
                                                    <?php echo e(single_price($order->grand_total)); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->orderDetails->first()->delivery_status)))); ?>

                                                    <?php if($order->delivery_viewed == 0): ?>
                                                        <span class="ml-2" style="color:green"><strong>*</strong></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($order->payment_status == 'paid'): ?>
                                                        <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                                                    <?php endif; ?>
                                                    <?php if($order->payment_status_viewed == 0): ?>
                                                        <span class="ml-2" style="color:green"><strong>*</strong></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if($order->orderDetails->first()->delivery_status == 'pending' && $order->payment_status == 'unpaid'): ?>
                                                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('orders.destroy', $order->id)); ?>" title="<?php echo e(translate('Cancel')); ?>">
                                                           <i class="las la-trash"></i>
                                                       </a>
                                                    <?php endif; ?>
                                                    <a href="javascript:void(0)" class="btn btn-soft-info btn-icon btn-circle btn-sm" onclick="show_purchase_history_details(<?php echo e($order->id); ?>)" title="<?php echo e(translate('Order Details')); ?>">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                    <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="<?php echo e(route('invoice.download', $order->id)); ?>" title="<?php echo e(translate('Download Invoice')); ?>">
                                                        <i class="las la-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                	<?php echo e($orders->links()); ?>

                          	</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $('#order_details').on('hidden.bs.modal', function () {
            location.reload();
        })
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/purchase_history.blade.php ENDPATH**/ ?>