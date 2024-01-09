
<?php $__env->startSection('content'); ?>

    <h3 class="text-center text-muted"><?php echo e(__('Activate OTP')); ?></h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Broker API')); ?></h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'broker_api')" <?php if(\App\OtpConfiguration::where('type', 'broker_api')->first()->value == 1): ?> checked <?php endif; ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <h3 class="text-center text-muted"><?php echo e(__('OTP will be Used For')); ?></h3>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Order Placement')); ?></h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_order')" <?php if(\App\OtpConfiguration::where('type', 'otp_for_order')->first()->value == 1): ?> checked <?php endif; ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Delivery Status Changing Time')); ?></h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_delivery_status')" <?php if(\App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->value == 1): ?> checked <?php endif; ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6"><?php echo e(__('Paid Status Changing Time')); ?></h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_paid_status')" <?php if(\App\OtpConfiguration::where('type', 'otp_for_paid_status')->first()->value == 1): ?> checked <?php endif; ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function updateSettings(el, type){
            if($(el).is(':checked')){
                var value = 1;
            }
            else{
                var value = 0;
            }
            $.post('<?php echo e(route('otp_configurations.update.activation')); ?>', {_token:'<?php echo e(csrf_token()); ?>', type:type, value:value}, function(data){
                if(data == '1'){
                    showAlert('success', 'Settings updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/otp_systems/configurations/activation.blade.php ENDPATH**/ ?>