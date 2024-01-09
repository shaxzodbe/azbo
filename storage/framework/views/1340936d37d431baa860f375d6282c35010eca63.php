

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Broker API')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="broker_api">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="BROKER_API_USERNAME">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('BROKER API USERNAME')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="BROKER_API_USERNAME" value="<?php echo e(env('BROKER_API_USERNAME')); ?>"
                                placeholder="BROKER API USERNAME" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="BROKER_API_PASSWORD">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('BROKER API PASSWORD')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="BROKER_API_PASSWORD"
                                value="<?php echo e(env('BROKER_API_PASSWORD')); ?>" placeholder="BROKER API PASSWORD" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Twillo Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="twillo">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_SID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO SID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_SID" value="<?php echo e(env('TWILIO_SID')); ?>"
                                placeholder="TWILIO SID" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_AUTH_TOKEN">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO AUTH TOKEN')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_AUTH_TOKEN"
                                value="<?php echo e(env('TWILIO_AUTH_TOKEN')); ?>" placeholder="TWILIO AUTH TOKEN" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_VERIFY_SID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO VERIFY SID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_VERIFY_SID"
                                value="<?php echo e(env('TWILIO_VERIFY_SID')); ?>" placeholder="TWILIO VERIFY SID">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="VALID_TWILLO_NUMBER">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('VALID TWILLO NUMBER')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="VALID_TWILLO_NUMBER"
                                value="<?php echo e(env('VALID_TWILLO_NUMBER')); ?>" placeholder="VALID TWILLO NUMBER">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Nexmo Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="nexmo">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="NEXMO_KEY">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('NEXMO KEY')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="NEXMO_KEY" value="<?php echo e(env('NEXMO_KEY')); ?>"
                                placeholder="NEXMO KEY" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="NEXMO_SECRET">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('NEXMO SECRET')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="NEXMO_SECRET"
                                value="<?php echo e(env('NEXMO_SECRET')); ?>" placeholder="NEXMO SECRET" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Twillo Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="twillo">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_SID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO SID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_SID" value="<?php echo e(env('TWILIO_SID')); ?>"
                                placeholder="TWILIO SID" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_AUTH_TOKEN">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO AUTH TOKEN')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_AUTH_TOKEN"
                                value="<?php echo e(env('TWILIO_AUTH_TOKEN')); ?>" placeholder="TWILIO AUTH TOKEN" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWILIO_VERIFY_SID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('TWILIO VERIFY SID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="TWILIO_VERIFY_SID"
                                value="<?php echo e(env('TWILIO_VERIFY_SID')); ?>" placeholder="TWILIO VERIFY SID">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="VALID_TWILLO_NUMBER">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('VALID TWILLO NUMBER')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="VALID_TWILLO_NUMBER"
                                value="<?php echo e(env('VALID_TWILLO_NUMBER')); ?>" placeholder="VALID TWILLO NUMBER">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Nexmo Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="nexmo">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="NEXMO_KEY">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('NEXMO KEY')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="NEXMO_KEY" value="<?php echo e(env('NEXMO_KEY')); ?>"
                                placeholder="NEXMO KEY" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="NEXMO_SECRET">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('NEXMO SECRET')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="NEXMO_SECRET"
                                value="<?php echo e(env('NEXMO_SECRET')); ?>" placeholder="NEXMO SECRET" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('SSL Wireless Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="ssl_wireless">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SSL_SMS_USER">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('SSL SMS USER')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="SSL_SMS_USER"
                                value="<?php echo e(env('SSL_SMS_USER')); ?>" placeholder="SSL SMS USER" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SSL_SMS_SID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('SSL SMS SID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="SSL_SMS_SID" value="<?php echo e(env('SSL_SMS_SID')); ?>"
                                placeholder="SSL SMS SID" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SSL_SMS_PASSWORD">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('SSL SMS PASSWORD')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="SSL_SMS_PASSWORD"
                                value="<?php echo e(env('SSL_SMS_PASSWORD')); ?>" placeholder="SSL SMS PASSWORD">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SSL_SMS_URL">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('SSL SMS URL')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="SSL_SMS_URL" value="<?php echo e(env('SSL_SMS_URL')); ?>"
                                placeholder="SSL SMS URL">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6"><?php echo e(__('Fast2SMS Credential')); ?></h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('update_credentials')); ?>" method="POST">
                    <input type="hidden" name="otp_method" value="fast2sms">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="AUTH_KEY">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('AUTH KEY')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="AUTH_KEY" value="<?php echo e(env('AUTH_KEY')); ?>"
                                placeholder="AUTH KEY" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="ROUTE">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('ROUTE')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="demo-select2" name="ROUTE" required>
                                <option value="p" <?php if(env('ROUTE')=="p" ): ?> selected <?php endif; ?>>Promotional Use</option>
                                <option value="t" <?php if(env('ROUTE')=="t" ): ?> selected <?php endif; ?>>Transactional Use</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="LANGUAGE">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('LANGUAGE')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="demo-select2" name="LANGUAGE" required>
                                <option value="english" <?php if(env('LANGUAGE')=="english" ): ?> selected <?php endif; ?>>English
                                </option>
                                <option value="unicode" <?php if(env('LANGUAGE')=="unicode" ): ?> selected <?php endif; ?>>Unicode
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SENDER_ID">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('SENDER ID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="SENDER_ID" value="<?php echo e(env('SENDER_ID')); ?>"
                                placeholder="6 digit SENDER ID">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-sm btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/otp_systems/configurations/index.blade.php ENDPATH**/ ?>