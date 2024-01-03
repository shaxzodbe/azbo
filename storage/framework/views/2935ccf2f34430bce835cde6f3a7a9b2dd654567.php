

<?php $__env->startSection('content'); ?>
<?php
    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
?>
<div class="card">
      <form class="" action="" method="GET">
        <div class="card-header row gutters-5">
          <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6"><?php echo e(translate('All Orders')); ?></h5>
          </div>
          <div class="col-lg-2">
              <div class="form-group mb-0">
                  <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>" name="date" placeholder="<?php echo e(translate('Filter by date')); ?>" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
              </div>
          </div>
          <div class="col-lg-2">
            <div class="form-group mb-0">
              <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type Order code & hit Enter')); ?>">
            </div>
          </div>
          <div class="col-auto">
            <div class="form-group mb-0">
              <button type="submit" class="btn btn-primary"><?php echo e(translate('Filter')); ?></button>
            </div>
          </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(translate('Order Code')); ?></th>
                    <th data-breakpoints="md"><?php echo e(translate('Num. of Products')); ?></th>
                    <th data-breakpoints="md"><?php echo e(translate('Customer')); ?></th>
                    <th data-breakpoints="md"><?php echo e(translate('Amount')); ?></th>
                    <th data-breakpoints="md"><?php echo e(translate('Delivery Status')); ?></th>
                    <th data-breakpoints="md"><?php echo e(translate('Payment Status')); ?></th>
                    <?php if($refund_request_addon != null && $refund_request_addon->activated == 1): ?>
                        <th><?php echo e(translate('Refund')); ?></th>
                    <?php endif; ?>
                    <th class="text-right" width="15%"><?php echo e(translate('options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e(($key+1) + ($orders->currentPage() - 1)*$orders->perPage()); ?>

                        </td>
                        <td>
                            <?php echo e($order->code); ?>

                        </td>
                        <td>
                            <?php echo e(count($order->orderDetails)); ?>

                        </td>
                        <td>
                            <?php if($order->user != null): ?>
                                <?php echo e($order->user->name); ?>

                            <?php else: ?>
                                Guest (<?php echo e($order->guest_id); ?>)
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e(single_price($order->grand_total)); ?>

                        </td>
                        <td>
                            <?php
                                $status = 'Delivered';
                                foreach ($order->orderDetails as $key => $orderDetail) {
                                    if($orderDetail->delivery_status != 'delivered'){
                                        $status = 'Pending';
                                    }
                                }
                            ?>
                            <?php echo e(translate($status)); ?>

                        </td>
                        <td>
                            <?php if($order->payment_status == 'paid'): ?>
                                <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                            <?php else: ?>
                                <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if($refund_request_addon != null && $refund_request_addon->activated == 1): ?>
                            <td>
                                <?php if(count($order->refund_requests) > 0): ?>
                                    <?php echo e(count($order->refund_requests)); ?> <?php echo e(translate('Refund')); ?>

                                <?php else: ?>
                                    <?php echo e(translate('No Refund')); ?>

                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('all_orders.show', encrypt($order->id))); ?>" title="<?php echo e(translate('View')); ?>">
                                <i class="las la-eye"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('invoice.download', $order->id)); ?>" title="<?php echo e(translate('Download Invoice')); ?>">
                                <i class="las la-download"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('orders.destroy', $order->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($orders->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/sales/all_orders/index.blade.php ENDPATH**/ ?>