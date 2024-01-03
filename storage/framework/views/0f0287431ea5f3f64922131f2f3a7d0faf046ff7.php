<?php $__env->startSection('content'); ?>

    <section class="py-4 gry-bg">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-8 mx-auto text-left">
                    <div class="shadow-sm bg-white p-4 rounded mb-4">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo e(route('one_click_payment')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" value="<?php echo e($product->id); ?>" name="product_id">
                            <div class="card shadow-0 border p-4">
                                <h5 class="card-title mb-3">1. To'lov ma'lumotlari</h5>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                        <p class="mb-2">
                                            <b>Telefon</b> <strong class="text-danger">*</strong>
                                        </p>
                                        <div class="form-outline">
                                            <input type="tel" name="phone" value="+998"
                                                   class="form-control placeholder-active active" required>

                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                        <p class="mb-2">
                                            <b>F.I.SH</b> <strong class="text-danger">*</strong>
                                        </p>
                                        <div class="form-outline">
                                            <input type="text" name="name" placeholder="F.I.Sh "
                                                   class="form-control placeholder-active">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="form-outline">
                                            <p class="mb-2">
                                                <b>Manzil: </b> <strong class="text-danger">*</strong>
                                            </p>
                                            <textarea class="form-control"
                                                      rows="3" name="address"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-outline">
                                            <p class="mb-2">
                                                <b>Qo'shimcha: </b>
                                            </p>
                                            <textarea class="form-control" name="addition" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" value="1" name="to_home"
                                                   id="check_to_home">
                                            <label class="form-check-label" for="check_to_home">
                                                <b> Uygacha yetkazib berish 20000 so'm</b>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                        <div class="form-outline">
                                            <input type="text" name="promo_kod" placeholder="Promo-kod bo'lsa kiriting"
                                                   class="form-control placeholder-active">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-0 border p-4">
                                <h5 class="card-title mb-3">2. To'lov usuli:</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                           id="pay_home" value="1" checked>
                                    <label class="form-check-label fs-16" for="pay_home">
                                        Mahsulotni olganda (naqd)
                                    </label>
                                </div>
                                <div class="form-check mb-3 ">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                           id="pay_cart" value="0">
                                    <label class="form-check-label fs-16" for="pay_cart">
                                        Karta orqali onlayn to'lov (UzCard, Humo, Visa, MasterCard)
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="agree_to_buy"
                                               id="agree_to_buy">
                                        <label class="form-check-label fs-16" for="agree_to_buy">
                                            Xarid qilish qoidalariga roziman <strong class="text-danger">*</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center text-md-right">
                                    <button class="btn btn-primary fw-600" type="submit"> Buyurtma qilish</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-header">
                            <h3 class="fs-16 fw-600 mb-0">Tanlangan mahsulot</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                $photos = explode(',',$product->photos);
                            ?>
                            <span class="d-flex align-items-center">
                                 <img
                                     src="<?php echo e(uploaded_asset($photos[0])); ?>"
                                     data-src="<?php echo e(uploaded_asset($photos[0])); ?>"
                                     class="img-fit size-60px rounded ls-is-cached lazyloaded"
                                     alt="<?php echo e($product->getTranslation('name')); ?>">
                                                        <span class="minw-0 pl-2 flex-grow-1">
                                                            <span class="fw-600 mb-1 text-truncate-2">
                                                                    <?php echo e($product->getTranslation('name')); ?>

                                                            </span>
                                                            <span class="">1x</span>
                                                            <span
                                                                class=""><?php echo e(home_discounted_price($product->id)); ?></span>
                                                        </span>

                                                </span>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/oneClick.blade.php ENDPATH**/ ?>