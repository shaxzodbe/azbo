

<?php $__env->startSection('content'); ?>
  <section class="py-5">
      <div class="container">
          <div class="d-flex align-items-start">
              <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <div class="aiz-user-panel">
                  <div class="aiz-titlebar mt-2 mb-4">
                    <div class="row align-items-center">
                      <div class="col-md-6">
                          <h1 class="h3"><?php echo e(translate('My Wallet')); ?></h1>
                      </div>
                    </div>
                  </div>
                  <div class="row gutters-10">
                      <div class="col-md-4 mx-auto mb-3" >
                          <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                            <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                                <i class="las la-dollar-sign la-2x text-white"></i>
                            </span>
                            <div class="px-3 pt-3 pb-3">
                                <div class="h4 fw-700 text-center"><?php echo e(single_price(Auth::user()->balance)); ?></div>
                                <div class="opacity-50 text-center"><?php echo e(translate('Wallet Balance')); ?></div>
                            </div>
                          </div>
                      </div>
                      <div class="col-md-4 mx-auto mb-3" >
                        <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition" onclick="show_wallet_modal()">
                            <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                <i class="las la-plus la-3x text-white"></i>
                            </span>
                            <div class="fs-18 text-primary"><?php echo e(translate('Recharge Wallet')); ?></div>
                        </div>
                      </div>
                      <?php if(\App\Addon::where('unique_identifier', 'offline_payment')->first() != null && \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated): ?>
                          <div class="col-md-4 mx-auto mb-3" >
                              <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition" onclick="show_make_wallet_recharge_modal()">
                                  <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                      <i class="las la-plus la-3x text-white"></i>
                                  </span>
                                  <div class="fs-18 text-primary"><?php echo e(translate('Offline Recharge Wallet')); ?></div>
                              </div>
                          </div>
                      <?php endif; ?>
                  </div>
                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0 h6"><?php echo e(translate('Wallet recharge history')); ?></h5>
                      </div>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                  <tr>
                                      <th>#</th>
                                      <th data-breakpoints="lg"><?php echo e(translate('Date')); ?></th>
                                      <th><?php echo e(translate('Amount')); ?></th>
                                      <th data-breakpoints="lg"><?php echo e(translate('Payment Method')); ?></th>
                                      <th class="text-right"><?php echo e(translate('Approval')); ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <tr>
                                          <td><?php echo e($key+1); ?></td>
                                          <td><?php echo e(date('d-m-Y', strtotime($wallet->created_at))); ?></td>
                                          <td><?php echo e(single_price($wallet->amount)); ?></td>
                                          <td><?php echo e(ucfirst(str_replace('_', ' ', $wallet ->payment_method))); ?></td>
                                          <td class="text-right">
                                              <?php if($wallet->offline_payment): ?>
                                                  <?php if($wallet->approval): ?>
                                                      <span class="badge badge-inline badge-success"><?php echo e(translate('Approved')); ?></span>
                                                  <?php else: ?>
                                                      <span class="badge badge-inline badge-info"><?php echo e(translate('Pending')); ?></span>
                                                  <?php endif; ?>
                                              <?php else: ?>
                                                  N/A
                                              <?php endif; ?>
                                          </td>
                                      </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                <?php echo e($wallets->links()); ?>

                            </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>

  <div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Recharge Wallet')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form class="" action="<?php echo e(route('wallet.recharge')); ?>" method="post">
                  <?php echo csrf_field(); ?>
                  <div class="modal-body gry-bg px-3 pt-3">
                      <div class="row">
                          <div class="col-md-4">
                              <label><?php echo e(translate('Amount')); ?> <span class="text-danger">*</span></label>
                          </div>
                          <div class="col-md-8">
                              <input type="number" lang="en" class="form-control mb-3" name="amount" placeholder="<?php echo e(translate('Amount')); ?>" required>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-4">
                              <label><?php echo e(translate('Payment Method')); ?> <span class="text-danger">*</span></label>
                          </div>
                          <div class="col-md-8">
                              <div class="mb-3">
                                  <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="payment_option" data-live-search="true">
                                      <?php if(\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1): ?>
                                          <option value="paypal"><?php echo e(translate('Paypal')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1): ?>
                                          <option value="stripe"><?php echo e(translate('Stripe')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1): ?>
                                          <option value="sslcommerz"><?php echo e(translate('SSLCommerz')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1): ?>
                                          <option value="instamojo"><?php echo e(translate('Instamojo')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'paystack')->first()->value == 1): ?>
                                          <option value="paystack"><?php echo e(translate('Paystack')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1): ?>
                                          <option value="voguepay"><?php echo e(translate('VoguePay')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'payhere')->first()->value == 1): ?>
                                          <option value="payhere"><?php echo e(translate('Payhere')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'ngenius')->first()->value == 1): ?>
                                          <option value="ngenius"><?php echo e(translate('Ngenius')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1): ?>
                                          <option value="razorpay"><?php echo e(translate('Razorpay')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'iyzico')->first()->value == 1): ?>
                                          <option value="iyzico"><?php echo e(translate('Iyzico')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'bkash')->first()->value == 1): ?>
                                          <option value="bkash"><?php echo e(translate('Bkash')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\BusinessSetting::where('type', 'nagad')->first()->value == 1): ?>
                                          <option value="nagad"><?php echo e(translate('Nagad')); ?></option>
                                      <?php endif; ?>
                                      <?php if(\App\Addon::where('unique_identifier', 'african_pg')->first() != null && \App\Addon::where('unique_identifier', 'african_pg')->first()->activated): ?>
                                          <?php if(\App\BusinessSetting::where('type', 'mpesa')->first()->value == 1): ?>
                                              <option value="mpesa"><?php echo e(translate('Mpesa')); ?></option>
                                          <?php endif; ?>
                                          <?php if(\App\BusinessSetting::where('type', 'flutterwave')->first()->value == 1): ?>
                                              <option value="flutterwave"><?php echo e(translate('Flutterwave')); ?></option>
                                          <?php endif; ?>
                                          <?php if(\App\BusinessSetting::where('type', 'payfast')->first()->value == 1): ?>
                                              <option value="payfast"><?php echo e(translate('PayFast')); ?></option>
                                          <?php endif; ?>
                                      <?php endif; ?>
                                      <?php if(\App\Addon::where('unique_identifier', 'paytm')->first() != null && \App\Addon::where('unique_identifier', 'paytm')->first()->activated): ?>
                                          <option value="paytm"><?php echo e(translate('Paytm')); ?></option>
                                      <?php endif; ?>
                                  </select>
                              </div>
                          </div>
                      </div>
                      <div class="form-group text-right">
                          <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1"><?php echo e(translate('Confirm')); ?></button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>


  <!-- offline payment Modal -->
  <div class="modal fade" id="offline_wallet_recharge_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Offline Recharge Wallet')); ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <div id="offline_wallet_recharge_modal_body"></div>
          </div>
      </div>
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function show_wallet_modal(){
            $('#wallet_modal').modal('show');
        }

        function show_make_wallet_recharge_modal(){
            $.post('<?php echo e(route('offline_wallet_recharge_modal')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
                $('#offline_wallet_recharge_modal_body').html(data);
                $('#offline_wallet_recharge_modal').modal('show');
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/wallet/index.blade.php ENDPATH**/ ?>