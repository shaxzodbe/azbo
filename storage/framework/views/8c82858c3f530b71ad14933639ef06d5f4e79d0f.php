

<?php $__env->startSection('content'); ?>
<?php
    $club_point_convert_rate = \App\BusinessSetting::where('type', 'club_point_convert_rate')->first();
?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Convert Point To Wallet')); ?></h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('point_convert_rate_store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="club_point_convert_rate">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="control-label"><?php echo e(__('Set Point For ')); ?> <?php echo e(single_price(1)); ?></label>
                            </div>
                            <div class="col-lg-5">
                                <input type="number" min="0" step="0.01" class="form-control" name="value" <?php if($club_point_convert_rate != null): ?> value="<?php echo e($club_point_convert_rate->value); ?>" <?php endif; ?> placeholder="100" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="control-label"><?php echo e(__('Points')); ?></label>
                            </div>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </form>
                    <i class="fs-AZ"><b><?php echo e(__('Note: You need to activate wallet option first before using club point addon.')); ?></b></i>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/club_points/config.blade.php ENDPATH**/ ?>