

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <table class="table aiz-table mb-0 footable footable-1 breakpoint-lg" cellspacing="0" width="100%">
                        <thead>
                            <tr class="footable-header">
                                <th>#</th>
                                <th width="20%"><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Product Owner')); ?></th>
                                <th><?php echo e(__('Num of Sale')); ?></th>
                                <th><?php echo e(__('Base Price')); ?></th>
                                <th><?php echo e(__('Rating')); ?></th>
                                <th><?php echo e(__('Point')); ?></th>
                                <th><?php echo e(__('Options')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(($key+1) + ($products->currentPage() - 1)*$products->perPage()); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('product', $product->slug)); ?>" target="_blank" class="media-block">
                                            <div class="media-left">
                                                <img loading="lazy"  class="img-md w-50px" src="<?php echo e(asset($product->thumbnail_img)); ?>" alt="Image">
                                            </div>
                                            <div class="media-body"><?php echo e(__($product->name)); ?></div>
                                        </a>
                                    </td>
                                    <td>
                                    <?php if($product->user != null): ?>
                                        <?php echo e($product->user->name); ?>

                                    <?php endif; ?>
                                    </td>
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
                                    <td><?php echo e(number_format($product->unit_price,2)); ?></td>
                                    <td><?php echo e($product->rating); ?></td>
                                    <td><?php echo e($product->earn_point); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('product_club_point.edit', encrypt($product->id))); ?>" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                            <i class="las la-edit"></i>
                                        </a>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="pull-right">
                            <?php echo e($products->appends(request()->input())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Set Point for Product')); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <small>Set any specific point for those products what are between 'min price' and 'max price'. Min-price should be less than Max-price</small>
                    </div>
                    <form class="form-horizontal" action="<?php echo e(route('set_products_point.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="control-label"><?php echo e(__('Set Point for multiple products')); ?></label>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" min="0" step="0.01" class="form-control" name="point" placeholder="100" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="control-label"><?php echo e(__('Min Price')); ?></label>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" min="0" step="0.01" class="form-control" name="min_price" value="<?php echo e(\App\Product::min('unit_price')); ?>" placeholder="50" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="control-label"><?php echo e(__('Max Price')); ?></label>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" min="0" step="0.01" class="form-control" name="max_price" value="<?php echo e(\App\Product::max('unit_price')); ?>" placeholder="110" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/club_points/set_point.blade.php ENDPATH**/ ?>