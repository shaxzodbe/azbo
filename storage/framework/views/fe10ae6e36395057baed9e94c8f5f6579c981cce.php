

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                <div class="bg-white rounded shadow-sm p-4 text-left">
                    <h1 class="h3 fw-600 mb-3"><?php echo e(translate('Verify Your Email Address')); ?></h1>
                    <p class="opacity-60">
                        <?php echo e(translate('Before proceeding, please check your email for a verification link.')); ?>

                        <?php echo e(translate('If you did not receive the email.')); ?>

                    </p>
                    <a href="<?php echo e(route('verification.resend')); ?>" class="btn btn-primary btn-block"><?php echo e(translate('Click here to request another')); ?></a>
                    <?php if(session('resent')): ?>
                        <div class="alert alert-success mt-2 mb-0" role="alert">
                            <?php echo e(translate('A fresh verification link has been sent to your email address.')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/auth/verify.blade.php ENDPATH**/ ?>