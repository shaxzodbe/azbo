
<?php $__env->startSection('robots'); ?><?php echo e(translate('index')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="d-flex align-items-start">
                <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="aiz-user-panel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Download Your Product')); ?></h5>
                        </div>
                        <div class="card-body">
                          <table class="table aiz-table mb-0">
                              <thead>
                                  <tr>
                                      <th><?php echo e(translate('Product')); ?></th>
                                      <th width="20%"><?php echo e(translate('Option')); ?></th>
                                  </tr>
                              </thead>
                              <tbody>
                                
                                  <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <?php
                                          $order = \App\OrderDetail::find($order_id->id);
                                      ?>
                                      <tr>
                                          <td><a href="<?php echo e(route('product', $order->product->slug)); ?>"><?php echo e($order->product->getTranslation('name')); ?></a></td>
                                          <td>
                                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="<?php echo e(route('digitalproducts.download', encrypt($order->product->id))); ?>" title="<?php echo e(translate('Download')); ?>">
                                                <i class="las la-download"></i>
                                            </a>
                                          </td>
                                      </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </tbody>
                          </table>
                            <?php echo e($orders->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/digital_purchase_history.blade.php ENDPATH**/ ?>