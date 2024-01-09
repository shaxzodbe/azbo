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
                        <?php if(count($selected_intend) > 0): ?>
                            <div class="card-body">
                                <table class="table aiz-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Product Name</th>
                                        <th data-breakpoints="md">Product Price</th>
                                        <th data-breakpoints="md">Product Monthly Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php $__currentLoopData = $selected_intend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <img class="w-80px"
                                                     src="<?php echo e(uploaded_asset($intend->products->thumbnail_img)); ?>"
                                                     alt="">
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('product',['slug'=>$intend->products->slug])); ?>" class="text-black">
                                                    <h6><?php echo e($intend->products->name); ?></h6>
                                                </a>
                                            </td>
                                            <td><?php echo e($intend->product_price); ?></td>
                                            <td><?php echo e($intend->product_monthly_price); ?></td>
                                        </tr>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                </table>
                                <div class="aiz-pagination">
                                    <?php echo e($selected_intend->links()); ?>

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

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/intend/intent_selected_products.blade.php ENDPATH**/ ?>