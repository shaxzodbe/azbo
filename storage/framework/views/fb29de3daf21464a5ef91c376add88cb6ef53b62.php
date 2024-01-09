<?php $__env->startSection('content'); ?>
<section class="pt-5 mb-4">
  <div class="container  pb-4 pt-md-4 min-vh-80">
    <div class="row">

      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4><?php echo e(translate('Order Code:')); ?> <span class="text-danger">#<?php echo e($data['code']); ?></span> </h4>
            
          </div>
          <div class="card-body">
            <h5>
              <?php echo e(translate('Thank you, you request has been placed successfully')); ?> !
            </h5>
            <p><?php echo e(translate('Our guys will contact you as soon as possible to confirm the order.')); ?></p>
          </div>
        </div>
      </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/installments/application_confirmed.blade.php ENDPATH**/ ?>