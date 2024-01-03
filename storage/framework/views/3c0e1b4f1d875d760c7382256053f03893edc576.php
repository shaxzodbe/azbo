<div class="modal-body">

  <div class="text-center">
      <span class="avatar avatar-xxl mb-3">
          <img src="<?php echo e(uploaded_asset($seller->user->avatar_original)); ?>">
      </span>
      <h1 class="h5 mb-1"><?php echo e($seller->user->name); ?></h1>
      <p class="text-sm text-muted"><?php echo e($seller->user->shop->name); ?></p>

      <div class="pad-ver btn-groups">
          <a href="<?php echo e($seller->user->shop->facebook); ?>" class="btn btn-icon demo-pli-facebook icon-lg add-tooltip" data-original-title="Facebook" data-container="body"></a>
          <a href="<?php echo e($seller->user->shop->twitter); ?>" class="btn btn-icon demo-pli-twitter icon-lg add-tooltip" data-original-title="Twitter" data-container="body"></a>
          <a href="<?php echo e($seller->user->shop->google); ?>" class="btn btn-icon demo-pli-google-plus icon-lg add-tooltip" data-original-title="Google+" data-container="body"></a>
      </div>
  </div>
  <hr>

  <!-- Profile Details -->
  <h6 class="mb-4"><?php echo e(translate('About')); ?> <?php echo e($seller->user->name); ?></h6>
  <p><i class="demo-pli-map-marker-2 icon-lg icon-fw mr-1"></i><?php echo e($seller->user->shop->address); ?></p>
  <p><a href="<?php echo e(route('shop.visit', $seller->user->shop->slug)); ?>" class="btn-link"><i class="demo-pli-internet icon-lg icon-fw mr-1"></i><?php echo e($seller->user->shop->name); ?></a></p>
  <p><i class="demo-pli-old-telephone icon-lg icon-fw mr-1"></i><?php echo e($seller->user->phone); ?></p>

  <h6 class="mb-4"><?php echo e(translate('Payout Info')); ?></h6>
  <p><?php echo e(translate('Bank Name')); ?> : <?php echo e($seller->bank_name); ?></p>
  <p><?php echo e(translate('Bank Acc Name')); ?> : <?php echo e($seller->bank_acc_name); ?></p>
  <p><?php echo e(translate('Bank Acc Number')); ?> : <?php echo e($seller->bank_acc_no); ?></p>
  <p><?php echo e(translate('Bank Routing Number')); ?> : <?php echo e($seller->bank_routing_no); ?></p>

  <br>

  <div class="table-responsive">
      <table class="table table-striped mar-no">
          <tbody>
          <tr>
              <td><?php echo e(translate('Total Products')); ?></td>
              <td><?php echo e(App\Product::where('user_id', $seller->user->id)->get()->count()); ?></td>
          </tr>
          <tr>
              <td><?php echo e(translate('Total Orders')); ?></td>
              <td><?php echo e(App\OrderDetail::where('seller_id', $seller->user->id)->get()->count()); ?></td>
          </tr>
          <tr>
              <td><?php echo e(translate('Total Sold Amount')); ?></td>
              <?php
                  $orderDetails = \App\OrderDetail::where('seller_id', $seller->user->id)->get();
                  $total = 0;
                  foreach ($orderDetails as $key => $orderDetail) {
                      if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                          $total += $orderDetail->price;
                      }
                  }
              ?>
              <td><?php echo e(single_price($total)); ?></td>
          </tr>
          <tr>
              <td><?php echo e(translate('Wallet Balance')); ?></td>
              <td><?php echo e(single_price($seller->user->balance)); ?></td>
          </tr>
          </tbody>
      </table>
  </div>
</div>
<?php /**PATH /var/www/azbo/resources/views/backend/sellers/profile_modal.blade.php ENDPATH**/ ?>