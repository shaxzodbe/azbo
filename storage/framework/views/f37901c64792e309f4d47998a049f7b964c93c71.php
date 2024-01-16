<div class="modal-body p-4 added-to-cart">
    <div class="text-center text-success mb-4">
        <i class="las la-check-circle la-3x"></i>
        <h3><?php echo e(translate('Item added to your cart!')); ?></h3>
    </div>
    <div class="media mb-4">
        <img src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>" data-src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" class="mr-3 lazyload size-100px img-fit rounded" alt="Product Image">
        <div class="media-body pt-3 text-left">
            <h6 class="fw-600">
                <?php echo e($product->getTranslation('name')); ?>

            </h6>
            <div class="row mt-3">
                <div class="col-sm-2 opacity-60">
                    <div><?php echo e(translate('Price')); ?>:</div>
                </div>
                <div class="col-sm-10">
                    <div class="h6 text-primary">
                        <strong>
                            <?php echo e(single_price(($data['price']+$data['tax'])*$data['quantity'])); ?>

                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-outline-primary mb-3 mb-sm-0" data-dismiss="modal"><?php echo e(translate('Back to shopping')); ?></button>
        
        <a href="<?php echo e(route('my_card')); ?>" class="btn btn-primary mb-3 mb-sm-0"><?php echo e(translate('Proceed to Checkout')); ?></a>
    </div>
</div>
<?php /**PATH /var/www/azbo/resources/views/frontend/partials/addedToCart.blade.php ENDPATH**/ ?>