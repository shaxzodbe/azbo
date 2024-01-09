

<?php $__env->startSection('content'); ?>

<section class="gry-bg py-5">
<div class="container">
    <div class="row">



<div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
    <div class="cls-content-sm card">
        
            <div class="card-body">
                <h1 class="h3"><?php echo e(__('Reset Password')); ?></h1>
                <p class="pad-btm"><?php echo e(__('Enter your phone, code and new password and confirm password.')); ?> </p>
                <form method="POST" action="<?php echo e(route('password.update.phone')); ?>">
                    <?php echo csrf_field(); ?>
    
                    <div class="form-group">
                        <input id="email" type="text" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="phone" value="<?php echo e($email ?? old('email')); ?>" placeholder="Phone" required autofocus>
    
                        <?php if($errors->has('email')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
    
                    <div class="form-group">
                        <input id="email" type="text" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="code" value="<?php echo e($email ?? old('email')); ?>" placeholder="Code" required autofocus>
    
                        <?php if($errors->has('email')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
    
                    <div class="form-group">
                        <input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" placeholder="New Password" required>
    
                        <?php if($errors->has('password')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('password')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
    
                    <div class="form-group">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
    
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <?php echo e(__('Reset Password')); ?>

                        </button>
                    </div>
                </form>
            </div>
        
    </div>
</div>


</div>
</div>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/otp_systems/frontend/auth/passwords/reset_with_phone.blade.php ENDPATH**/ ?>