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
                    <th data-breakpoints="lg">#</th>
                    <th data-breakpoints="lg"><?php echo e(translate('Product')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Customer')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Phone')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Address')); ?></th>

                    <th data-breakpoints="lg"><?php echo e(__('pagination.Created_at')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Status')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Options')); ?></th>

                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $productIntend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $one_click_order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($one_click_order != null): ?>
                        <tr>
                            <td>
                                <?php echo e($key+1); ?>

                            </td>
                            <td>
                                <?php if($one_click_order->product): ?>
                                    <?php
                                        $photos = explode(',',$one_click_order->product->photos);
                                    ?>
                                    <span class="d-flex align-items-center">
                                        <img
                                            src="<?php echo e(uploaded_asset($photos[0])); ?>"
                                            data-src="<?php echo e(uploaded_asset($photos[0])); ?>"
                                            class="img-fit size-60px rounded ls-is-cached lazyloaded"
                                            alt="<?php echo e($one_click_order->product->name); ?>">
                                        <span class="minw-0 pl-2 flex-grow-1">
                                            <span class="fw-600 mb-1 text-truncate-2">
                                                    <?php echo e($one_click_order->product->name); ?>

                                            </span>
                                            <span class="">1x</span>
                                            <span
                                                class=""><?php echo e(home_discounted_price($one_click_order->product->id)); ?></span>
                                        </span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $user = \App\User::where('id','=',$one_click_order->user_id)->first();
                                        if (!empty($user)){
                                            echo $user->name;
                                        }else{
                                         echo "empty";
                                        }
                                ?>
                            </td>
                            <td>
                                <?php if(!empty($one_click_order->user->phone)): ?>
                                <?php echo e($one_click_order->user->phone); ?>

                                <?php else: ?>
                                    <?php echo e('Empty'); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                               email 
                            </td>
                            <td>
                                <?php echo e($one_click_order->created_at); ?>

                            </td>
                            <td class="text-right">
                            <span id="payment_status"
                                  class="badge badge-inline <?php echo e(($one_click_order->status($one_click_order->status)['color'])); ?>"><?php echo e(($one_click_order->status($one_click_order->status)['value'])); ?></span>
                            </td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="<?php echo e(route('indend.show', encrypt($one_click_order->id))); ?>"
                                   title="<?php echo e(translate('View')); ?>">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   data-href="<?php echo e(route('one_click_orders.destroy', $one_click_order->id)); ?>"
                                   title="<?php echo e(translate('Delete')); ?>">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="aiz-pagination">
                <?php echo e($productIntend->links()); ?>

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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/sales/product_intend/index.blade.php ENDPATH**/ ?>