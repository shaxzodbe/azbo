

<?php $__env->startSection('content'); ?>


<div class="h-100 bg-cover bg-center py-5 d-flex align-items-center" style="background-image: url(<?php echo e(uploaded_asset(get_setting('admin_login_background'))); ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-4 mx-auto">
                <div class="card text-left">
                    <div class="card-header"><?php echo e(translate('Create a New Account')); ?></div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('register')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" required autofocus placeholder="<?php echo e(translate('Full Name')); ?>">

                                <?php if($errors->has('name')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required placeholder="<?php echo e(translate('password')); ?>">

                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required placeholder="<?php echo e(translate('Email')); ?>">

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="<?php echo e(translate('Confrim Password')); ?>">
                            </div>
                            <div class="checkbox pad-btm text-left">
                                <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" required>
                                <label for="demo-form-checkbox"><?php echo e(translate('I agree with the Terms and Conditions')); ?></label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <?php echo e(translate('Register')); ?>

                            </button>
                        </form>
                        <div class="mt-3">
                            <?php echo e(translate('Already have an account')); ?> ? <a href="<?php echo e(route('login')); ?>" class="btn-link mar-rgt text-bold"><?php echo e(translate('Sign In')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/auth/register.blade.php ENDPATH**/ ?>