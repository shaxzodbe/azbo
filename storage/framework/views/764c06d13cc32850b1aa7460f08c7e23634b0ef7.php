<div class="aiz-user-sidenav-wrap pt-4 position-relative z-1 shadow-sm">
    <div class="absolute-top-right d-xl-none">
        <button class="btn btn-sm p-2" data-toggle="class-toggle" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
    <div class="absolute-top-left d-xl-none">
        <a class="btn btn-sm p-2" href="<?php echo e(route('logout')); ?>">
            <i class="las la-sign-out-alt la-2x"></i>
        </a>
    </div>
    <div class="aiz-user-sidenav rounded overflow-hidden  c-scrollbar-light">
        <div class="px-4 text-center mb-4">
            <span class="avatar avatar-md mb-3">
                <?php if(Auth::user()->avatar_original != null): ?>
                    <img src="<?php echo e(uploaded_asset(Auth::user()->avatar_original)); ?>" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/avatar-place.png')); ?>';">
                <?php else: ?>
                    <img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>" class="image rounded-circle" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/avatar-place.png')); ?>';">
                <?php endif; ?>
            </span>

            <?php if(Auth::user()->user_type == 'customer'): ?>
                <h4 class="h5 fw-600"><?php echo e(Auth::user()->name); ?></h4>
            <?php else: ?>
                <h4 class="h5 fw-600"><?php echo e(Auth::user()->name); ?>

                    <span class="ml-2">
                        <?php if(Auth::user()->seller->verification_status == 1): ?>
                            <i class="las la-check-circle" style="color:green"></i>
                        <?php else: ?>
                            <i class="las la-times-circle" style="color:red"></i>
                        <?php endif; ?>
                    </span>
                </h4>
            <?php endif; ?>
        </div>

        <div class="sidemnenu mb-3">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('dashboard')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['dashboard'])); ?>">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Dashboard')); ?></span>
                    </a>
                </li>

                <?php
                    $delivery_viewed = App\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
                    $payment_status_viewed = App\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
                ?>
                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('purchase_history.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['purchase_history.index'])); ?>">
                        <i class="las la-file-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Purchase History')); ?></span>
                        <?php if($delivery_viewed > 0 || $payment_status_viewed > 0): ?><span class="badge badge-inline badge-success"><?php echo e(translate('New')); ?></span><?php endif; ?>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('digital_purchase_history.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['digital_purchase_history.index'])); ?>">
                        <i class="las la-download aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Downloads')); ?></span>
                    </a>
                </li>

                <?php
                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                    $club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
                ?>
                <?php if($refund_request_addon != null && $refund_request_addon->activated == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('customer_refund_request')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['customer_refund_request'])); ?>">
                            <i class="las la-backward aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Sent Refund Request')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('wishlists.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['wishlists.index'])); ?>">
                        <i class="la la-heart-o aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Wishlist')); ?></span>
                    </a>
                </li>

                <?php if(Auth::user()->user_type == 'seller'): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.products')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.products', 'seller.products.upload', 'seller.products.edit'])); ?>">
                            <i class="lab la-sketch aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Products')); ?></span>
                        </a>
                    </li>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('product_bulk_upload.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['product_bulk_upload.index'])); ?>">
                            <i class="las la-upload aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Product Bulk Upload')); ?></span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('product_price_bulk_upload.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['product_price_bulk_upload.index'])); ?>">
                            <i class="las la-upload aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Product Price Bulk Upload')); ?></span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('seller.digitalproducts')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit'])); ?>">
                            <i class="lab la-sketch aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Digital Products')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('customer_products.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['customer_products.index', 'customer_products.create', 'customer_products.edit'])); ?>">
                            <i class="lab la-sketch aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Classified Products')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Auth::user()->user_type == 'seller'): ?>
                    <?php if(\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated): ?>
                        <?php if(\App\BusinessSetting::where('type', 'pos_activation_for_seller')->first() != null && \App\BusinessSetting::where('type', 'pos_activation_for_seller')->first()->value != 0): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('poin-of-sales.seller_index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['poin-of-sales.seller_index'])); ?>">
                                    <i class="las la-fax aiz-side-nav-icon"></i>
                                    <span class="aiz-side-nav-text"><?php echo e(translate('POS Manager')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php
                        $orders = DB::table('orders')
                                    ->orderBy('code', 'desc')
                                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                    ->where('order_details.seller_id', Auth::user()->id)
                                    ->where('orders.viewed', 0)
                                    ->select('orders.id')
                                    ->distinct()
                                    ->count();
                    ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('orders.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['orders.index'])); ?>">
                            <i class="las la-money-bill aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Orders')); ?></span>
                            <?php if($orders > 0): ?><span class="badge badge-inline badge-success"><?php echo e($orders); ?></span><?php endif; ?>
                        </a>
                    </li>

                    <?php if($refund_request_addon != null && $refund_request_addon->activated == 1): ?>
                        <li class="aiz-side-nav-item">
                            <a href="<?php echo e(route('vendor_refund_request')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['vendor_refund_request','reason_show'])); ?>">
                                <i class="las la-backward aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text"><?php echo e(translate('Received Refund Request')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php
                        $review_count = DB::table('reviews')
                                    ->orderBy('code', 'desc')
                                    ->join('products', 'products.id', '=', 'reviews.product_id')
                                    ->where('products.user_id', Auth::user()->id)
                                    ->where('reviews.viewed', 0)
                                    ->select('reviews.id')
                                    ->distinct()
                                    ->count();
                    ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('reviews.seller')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['reviews.seller'])); ?>">
                            <i class="las la-star-half-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Product Reviews')); ?></span>
                            <?php if($review_count > 0): ?><span class="badge badge-inline badge-success"><?php echo e($review_count); ?></span><?php endif; ?>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('shops.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shops.index'])); ?>">
                            <i class="las la-cog aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Shop Setting')); ?></span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('payments.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['payments.index'])); ?>">
                            <i class="las la-history aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Payment History')); ?></span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('withdraw_requests.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['withdraw_requests.index'])); ?>">
                            <i class="las la-money-bill-wave-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Money Withdraw')); ?></span>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if(\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1): ?>
                    <?php
                        $conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
                    ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('conversations.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['conversations.index', 'conversations.show'])); ?>">
                            <i class="las la-comment aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Conversations')); ?></span>
                            <?php if(count($conversation) > 0): ?>
                                <span class="badge badge-success">(<?php echo e(count($conversation)); ?>)</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if(\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('wallet.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['wallet.index'])); ?>">
                            <i class="las la-dollar-sign aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('My Wallet')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($club_point_addon != null && $club_point_addon->activated == 1): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('earnng_point_for_user')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['earnng_point_for_user'])); ?>">
                            <i class="las la-dollar-sign aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Earning Points')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status): ?>
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['affiliate.user.index', 'affiliate.payment_settings'])); ?>">
                            <i class="las la-dollar-sign aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Affiliate')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('affiliate.user.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Affiliate System')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('affiliate.user.payment_history')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Payment History')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('affiliate.user.withdraw_request_history')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Withdraw request history')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php
                    $support_ticket = DB::table('tickets')
                                ->where('client_viewed', 0)
                                ->where('user_id', Auth::user()->id)
                                ->count();
                ?>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('support_ticket.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['support_ticket.index'])); ?>">
                        <i class="las la-atom aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Support Ticket')); ?></span>
                        <?php if($support_ticket > 0): ?><span class="badge badge-inline badge-success"><?php echo e($support_ticket); ?></span> <?php endif; ?>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('profile')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['profile'])); ?>">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Manage Profile')); ?></span>
                    </a>
                </li>

            </ul>
        </div>
        <?php if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1 && Auth::user()->user_type == 'customer'): ?>
            <div>
                <a href="<?php echo e(route('shops.create')); ?>" class="btn btn-block btn-soft-primary rounded-0">
                    </i><?php echo e(translate('Be A Seller')); ?>

                </a>
            </div>
        <?php endif; ?>
        <?php if(Auth::user()->user_type == 'seller'): ?>
          <hr>
          <h4 class="h5 fw-600 text-center"><?php echo e(translate('Sold Amount')); ?></h4>
          <!-- <div class="sidebar-widget-title py-3">
              <span></span>
          </div> -->
          <?php
              $date = date("Y-m-d");
              $days_ago_30 = date('Y-m-d', strtotime('-30 days', strtotime($date)));
              $days_ago_60 = date('Y-m-d', strtotime('-60 days', strtotime($date)));
          ?>
          <div class="widget-balance pb-3 pt-1">
            <div class="text-center">
                <div class="heading-4 strong-700 mb-4">
                    <?php
                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', $days_ago_30)->get();
                        $total = 0;
                        foreach ($orderDetails as $key => $orderDetail) {
                            if($orderDetail->order != null && $orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                $total += $orderDetail->price;
                            }
                        }
                    ?>
                    <small class="d-block fs-12 mb-2"><?php echo e(translate('Your sold amount (current month)')); ?></small>
                    <span class="btn btn-primary fw-600 fs-18"><?php echo e(single_price($total)); ?></span>
                </div>
                <table class="table table-borderless">
                    <tr>
                        <?php
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        ?>
                        <td class="p-1" width="60%">
                            <?php echo e(translate('Total Sold')); ?>:
                        </td>
                        <td class="p-1 fw-600" width="40%">
                            <?php echo e(single_price($total)); ?>

                        </td>
                    </tr>
                    <tr>
                        <?php
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', $days_ago_60)->where('created_at', '<=', $days_ago_30)->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        ?>
                        <td class="p-1" width="60%">
                            <?php echo e(translate('Last Month Sold')); ?>:
                        </td>
                        <td class="p-1 fw-600" width="40%">
                            <?php echo e(single_price($total)); ?>

                        </td>
                    </tr>
                </table>
            </div>
            <table>

            </table>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php /**PATH /var/www/azbo/resources/views/frontend/inc/user_side_nav.blade.php ENDPATH**/ ?>