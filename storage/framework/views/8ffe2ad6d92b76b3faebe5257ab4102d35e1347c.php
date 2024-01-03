

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-start">
            <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="aiz-user-panel">
                <div class="aiz-titlebar mt-2 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="h3"><?php echo e(translate('Dashboard')); ?></h1>
                        </div>
                    </div>
                  </div>

                  <div class="row gutters-10">
                    <div class="col-md-3">
                        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                          <div class="px-3 pt-3">
                            <div class="h3 fw-700">
                              <?php echo e(count(\App\Product::where('user_id', Auth::user()->id)->get())); ?>

                            </div>
                            <div class="opacity-50"><?php echo e(translate('Products')); ?></div>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                              <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,192L26.7,192C53.3,192,107,192,160,202.7C213.3,213,267,235,320,218.7C373.3,203,427,149,480,117.3C533.3,85,587,75,640,90.7C693.3,107,747,149,800,149.3C853.3,149,907,107,960,112C1013.3,117,1067,171,1120,202.7C1173.3,235,1227,245,1280,213.3C1333.3,181,1387,107,1413,69.3L1440,32L1440,320L1413.3,320C1386.7,320,1333,320,1280,320C1226.7,320,1173,320,1120,320C1066.7,320,1013,320,960,320C906.7,320,853,320,800,320C746.7,320,693,320,640,320C586.7,320,533,320,480,320C426.7,320,373,320,320,320C266.7,320,213,320,160,320C106.7,320,53,320,27,320L0,320Z"></path>
                          </svg>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="h3 fw-700">
                                  <?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get())); ?>

                                </div>
                                <div class="opacity-50"><?php echo e(translate('Total sale')); ?></div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,192L30,208C60,224,120,256,180,245.3C240,235,300,181,360,144C420,107,480,85,540,96C600,107,660,149,720,154.7C780,160,840,128,900,117.3C960,107,1020,117,1080,112C1140,107,1200,85,1260,74.7C1320,64,1380,64,1410,64L1440,64L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <?php
                                    $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                                    $total = 0;
                                    foreach ($orderDetails as $key => $orderDetail) {
                                        if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                            $total += $orderDetail->price;
                                        }
                                    }
                                ?>
                                <div class="h3 fw-700"><?php echo e(single_price($total)); ?></div>
                                <div class="opacity-50"><?php echo e(translate('Total earnings')); ?></div>
                              </div>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                  <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                              </svg>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                          <div class="px-3 pt-3">
                              <?php
                              $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                              $total = 0;
                              foreach ($orders as $key => $order) {
                              $total += count($order->orderDetails);
                              }
                              ?>
                              <div class="h3 fw-700">
                                  <?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get())); ?>

                              </div>
                              <div class="opacity-50"><?php echo e(translate('Successful orders')); ?></div>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                              <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,192L26.7,192C53.3,192,107,192,160,202.7C213.3,213,267,235,320,218.7C373.3,203,427,149,480,117.3C533.3,85,587,75,640,90.7C693.3,107,747,149,800,149.3C853.3,149,907,107,960,112C1013.3,117,1067,171,1120,202.7C1173.3,235,1227,245,1280,213.3C1333.3,181,1387,107,1413,69.3L1440,32L1440,320L1413.3,320C1386.7,320,1333,320,1280,320C1226.7,320,1173,320,1120,320C1066.7,320,1013,320,960,320C906.7,320,853,320,800,320C746.7,320,693,320,640,320C586.7,320,533,320,480,320C426.7,320,373,320,320,320C266.7,320,213,320,160,320C106.7,320,53,320,27,320L0,320Z"></path>
                          </svg>
                        </div>
                    </div>
              	  </div>

                  <div class="row">
                      <div class="col-md-7">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="mb-0 h6"><?php echo e(translate('Orders')); ?></h5>
                              </div>
                              <div class="card-body">
                                  <table class="table aiz-table mb-0">
                                      <tr>
                                          <td><?php echo e(translate('Total orders')); ?>:</td>
                                          <td><?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->get())); ?></strong></td>
                                      </tr>
                                      <tr>
                                          <td><?php echo e(translate('Pending orders')); ?>:</td>
                                          <td><?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'pending')->get())); ?></strong></td>
                                      </tr>
                                      <tr>
                                          <td><?php echo e(translate('Cancelled orders')); ?>:</td>
                                          <td><?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'cancelled')->get())); ?></strong></td>
                                      </tr>
                                      <tr>
                                          <td><?php echo e(translate('Successful orders')); ?>:</td>
                                          <td><?php echo e(count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get())); ?></strong></td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-5">
                          <div class="bg-white mt-4 p-5 text-center">
                              <div class="mb-3">
                                  <?php if(Auth::user()->seller->verification_status == 0): ?>
                                      <img loading="lazy"  src="<?php echo e(static_asset('assets/img/non_verified.png')); ?>" alt="" width="130">
                                  <?php else: ?>
                                      <img loading="lazy"  src="<?php echo e(static_asset('assets/img/verified.png')); ?>" alt="" width="130">
                                  <?php endif; ?>
                              </div>
                              <?php if(Auth::user()->seller->verification_status == 0): ?>
                                  <a href="<?php echo e(route('shop.verify')); ?>" class="btn btn-primary"><?php echo e(translate('Verify Now')); ?></a>
                              <?php endif; ?>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-8">
                          <div class="card">
                              <div class="card-header">
                                  <h6 class="mb-0"><?php echo e(translate('Products')); ?></h6>
                              </div>
            				          <div class="card-body">
                                <table class="table aiz-table mb-0">
                                  <thead>
                                      <tr>
                                          <th><?php echo e(translate('Category')); ?></th>
                                          <th><?php echo e(translate('Product')); ?></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php $__currentLoopData = \App\Category::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($category->products->where('user_id', Auth::user()->id))>0): ?>
                                          <tr>
                                              <td><?php echo e($category->getTranslation('name')); ?></td>
                                              <td><?php echo e(count($category->products->where('user_id', Auth::user()->id))); ?></td>
                                          </tr>
                                      <?php endif; ?>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                                <br>
                                <div class="text-center">
                                    <a href="<?php echo e(route('seller.products.upload')); ?>" class="btn btn-primary d-inline-block"><?php echo e(translate('Add New Product')); ?></a>
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <?php if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated): ?>

                              <div class="card">
                                  <div class="card-header">
                                      <h6 class="mb-0"><?php echo e(translate('Purchased Package')); ?></h6>
                                  </div>
                                  <?php
                                      $seller_package = \App\SellerPackage::find(Auth::user()->seller->seller_package_id);
                                  ?>
                                  <div class="card-body text-center">
                                      <?php if($seller_package != null): ?>
                                        <img src="<?php echo e(uploaded_asset($seller_package->logo)); ?>" class="img-fluid mb-4 h-110px">
                                        <p class="mb-1 text-muted"><?php echo e(translate('Product Upload Remaining')); ?>: <?php echo e(Auth::user()->seller->remaining_uploads); ?> <?php echo e(translate('Times')); ?></p>
                                        <p class="text-muted mb-1"><?php echo e(translate('Digital Product Upload Remaining')); ?>: <?php echo e(Auth::user()->seller->remaining_digital_uploads); ?> <?php echo e(translate('Times')); ?></p>
                                        <p class="text-muted mb-4"><?php echo e(translate('Package Expires at')); ?>: <?php echo e(Auth::user()->seller->invalid_at); ?></p>
                                        <h6 class="fw-600 mb-3 text-primary"><?php echo e(translate('Current Package')); ?>: <?php echo e($seller_package->name); ?></h6>
                                      <?php else: ?>
                                          <h6 class="fw-600 mb-3 text-primary"><?php echo e(translate('Package Not Found')); ?></h6>
                                      <?php endif; ?>
                                      <div class="text-center">
                                          <a href="<?php echo e(route('seller_packages_list')); ?>" class="btn btn-soft-primary"><?php echo e(translate('Upgrade Package')); ?></a>
                                      </div>
                                  </div>
                              </div>
                          <?php endif; ?>
                          <div class="bg-white mt-4 p-4 text-center">
                              <div class="h5 fw-600"><?php echo e(translate('Shop')); ?></div>
                              <p><?php echo e(translate('Manage & organize your shop')); ?></p>
                              <a href="<?php echo e(route('shops.index')); ?>" class="btn btn-soft-primary"><?php echo e(translate('Go to setting')); ?></a>
                          </div>
                          <div class="bg-white mt-4 p-4 text-center">
                              <div class="h5 fw-600"><?php echo e(translate('Payment')); ?></div>
                              <p><?php echo e(translate('Configure your payment method')); ?></p>
                              <a href="<?php echo e(route('profile')); ?>" class="btn btn-soft-primary"><?php echo e(translate('Configure Now')); ?></a>
                          </div>
                      </div>
                  </div>

              </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/seller/dashboard.blade.php ENDPATH**/ ?>