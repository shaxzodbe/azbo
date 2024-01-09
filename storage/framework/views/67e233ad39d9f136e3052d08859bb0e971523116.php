<?php $__env->startSection('content'); ?>

<div class="card">

  <div class="card-body">
    <table class="table aiz-table mb-0">
      <thead>
          <tr>
              <th>#</th>
              <th><?php echo e(translate('Payment Code')); ?></th>
              <th> Product Owner </th>
              <th><?php echo e(translate('Customer Name')); ?></th>
              <th data-breakpoints="md"><?php echo e(translate('Product Name')); ?> </th>
              <th data-breakpoints="md"><?php echo e(translate('Quantity')); ?></th>
              <th data-breakpoints="md"><?php echo e(translate('Amount')); ?></th>
              <th data-breakpoints="md"><?php echo e(translate('Period')); ?></th>
              <th data-breakpoints="md"><?php echo e(translate('Status')); ?></th>

              <th class="text-right" width="15%"><?php echo e(translate('options')); ?></th>
          </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($app->user): ?>
            <tr>
              <td><?php echo e($key + 1); ?> </td>
              <td><?php echo e($app->code); ?> </td>
              <td><?php echo e($app->product ? $app->product->user->name : '-'); ?></td>
              <td><?php echo e($app->user->name ?? ''); ?></td>
              <td><?php echo e(isset($app->product) ? $app->product->name ?? '' : ''); ?> <?php if($app->variant): ?> - <?php echo e(get_variant_string($app->variant)); ?> <?php endif; ?></td>
              <td><?php echo e($app->quantity); ?> </td>
              <td><?php echo e(single_price($app->price)); ?></td>

              <td><?php echo e(json_decode($app->details)->name ?? ''); ?></td>

              <td style="text-transform: capitalize">

                <?php if($app->status == 'pending'): ?>
                  <span class="badge badge-inline badge-primary"><?php echo e(translate($app->status)); ?></span>
                <?php elseif($app->status == 'approved'): ?>
                  <span class="badge badge-inline badge-success"><?php echo e(translate($app->status)); ?></span>
                <?php elseif($app->status == 'disapproved'): ?>
                  <span class="badge badge-inline badge-danger"><?php echo e(translate($app->status)); ?></span>
                <?php else: ?>
                <?php endif; ?>
                </td>

              <td class="text-right">
                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('monthly_payment.show', encrypt($app->id))); ?>" title="<?php echo e(translate('View')); ?>">
                  <i class="las la-eye"></i>
                </a>


                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('monthly_payment.customer.edit', $app->user->id)); ?>" title="<?php echo e(translate('Edit')); ?>">
                  <i class="las la-edit"></i>
              </a>
              </td>

            </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/monthly_payment/index.blade.php ENDPATH**/ ?>