<?php $__env->startSection('css'); ?>

<style>
  .condition-card.active {
    border-color: #ffc107 !important;
  }

  .condition-card {
    cursor: pointer;
    border-color: #ddd !important;
    border: 2px solid #f8f9fa;
    border-radius: 2px;
  }
</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="pt-5 mb-4">
  <div class="container  pb-4 pt-md-4 min-vh-80">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4><?php echo e(translate('Selected installment')); ?></h4>
          </div>
          <div class="card-body">

            <?php $__currentLoopData = $installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
              <div class="card condition-card <?php echo e($installment->id == $data['selected_installment_id'] ? 'active' : ''); ?>"
                onclick="cardClicked(this)" style="margin-bottom: 8px;"
                data-product_id=<?php echo e($product->id); ?> data-installment_id=<?php echo e($installment->id); ?> data-quantity=<?php echo e($data['quantity']); ?>>
                <div class="card-body">
                  <div class="mb-2">
                    <?php

                    $total_price = $data['discounted_price'] * $data['quantity'];

                    $profit = $total_price * $product->profit / 100;


                    $percent = $total_price * $installment->value / 100;


                    $final_price = ($total_price + $profit + $percent) / $installment->period;

                    ?>
                    <h5> <?php echo e(single_price($final_price)); ?> / <?php echo e($installment->label); ?></h5>
                  </div>
                  <div>
                    <!-- <span class="me-2"><?php echo e($installment->value); ?>% /</span> -->
                    <span><?php echo e(translate('Margin')); ?> - <?php echo e(single_price($percent)); ?></span>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


          </div>

          <div class="card-footer">
            <button id="btn_next" class="btn btn-primary" style="border-radius: 0px;" onclick="btn_next_clicked()"> <?php echo e(translate('Next')); ?></button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4><?php echo e(translate('Selected Product')); ?></h4>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush border-0 mb-3">
              <li class="list-group-item bg-transparent">
                <div class="row align-items-center">
                  <div class="col-12 col-md-auto">
                    <div class="d-flex justify-content-center"><img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>"
                        alt="" class="img-fluid" style="max-height: 100px;"></div>
                  </div>
                  <div class="col">
                    <span class="h6"><?php echo e($product->getTranslation('name')); ?> - <?php echo e(get_variant_string($data['variant'])); ?> </span>
                    <div class="text-secondary"><span class="d-block">
                        <?php echo e(single_price($data['discounted_price'])); ?> · x<?php echo e($data['quantity']); ?>

                      </span>
                      <?php if($data['discount'] != 0): ?>
                      <del>
                        <?php echo e(single_price($data['price'])); ?>

                      </del>
                      <small class="text-danger">
                        - <?php echo e(translate('discount')); ?> <?php echo e(single_price($data['discount'])); ?>

                      </small></div>

                    <?php endif; ?>
                  </div>
                </div>
              </li>
            </ul>

            <span class="h4 mb-0">
              <?php echo e(translate('Total')); ?> <?php echo e(single_price($data['discounted_price'] * $data['quantity'])); ?>

            </span>
          </div>
        </div>
      </div>
    </div>

    <form action="/applicationapp" method="POST" style="display: none;" id="form_next">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="product_id" id="product_id" value="">
      <input type="hidden" name="installment_id" id="installment_id" value="">
      <input type="hidden" name="quantity" id="quantity">
      <input type="hidden" name="variant" id="variant" value="<?php echo e($data['variant']); ?>">
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script>
  function cardClicked(el) {
    if(el.classList.contains('active')) {
      return;
    }
    var divs = document.querySelectorAll('.condition-card');
    divs.forEach(element => {
      element.classList.remove('active');
    })
    el.classList.add('active');
  }


  function btn_next_clicked() {
    var el = document.querySelector('.condition-card.active');
    if (el == null) {
      alert('<?php echo e(translate("Please select an installment !")); ?>')
      return
    }

    document.getElementById('product_id').value = el.dataset.product_id;
    document.getElementById('installment_id').value = el.dataset.installment_id;
    document.getElementById('quantity').value = el.dataset.quantity;


    document.getElementById('form_next').submit();
  }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/installments/select_installmentapp.blade.php ENDPATH**/ ?>