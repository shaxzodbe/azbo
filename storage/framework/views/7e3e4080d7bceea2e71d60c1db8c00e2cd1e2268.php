

<?php $__env->startSection('content'); ?>

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6"><?php echo e(translate('Add New Seller')); ?></h5>
    </div>

    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Seller Information')); ?></h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('sellers.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Name')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="<?php echo e(translate('Name')); ?>" id="name" name="name"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="phone"><?php echo e(translate('Phone')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="<?php echo e(translate('Phone')); ?>" id="phone" name="phone"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email"><?php echo e(translate('Email')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="<?php echo e(translate('Email')); ?>" id="email" name="email"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password"><?php echo e(translate('Password')); ?></label>
                        <div class="col-sm-9">
                            <input type="password" placeholder="<?php echo e(translate('Password')); ?>" id="password" name="password"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password"><?php echo e(translate('Monthly Price')); ?></label>
                        <div class="col-sm-3">
                            <input type="checkbox" style="width: 20px;" name="monthly_price" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/sellers/create.blade.php ENDPATH**/ ?>