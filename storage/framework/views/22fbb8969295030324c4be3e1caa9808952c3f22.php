

<?php $__env->startSection('content'); ?>
    <?php
        $club_point_convert_rate = \App\BusinessSetting::where('type', 'club_point_convert_rate')->first()->value;
    ?>
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    <?php if(Auth::user()->user_type == 'seller'): ?>
                        <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php elseif(Auth::user()->user_type == 'customer'): ?>
                        <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        <?php echo e(__('My Points')); ?>

                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                                            <li><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                                            <li class="active"><a href="<?php echo e(route('earnng_point_for_user')); ?>"><?php echo e(__('My Points')); ?></a></li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                    <span class="d-block title heading-3 strong-400 mb-3"><?php echo e($club_point_convert_rate); ?> <?php echo e(__(' Points')); ?> = <?php echo e(single_price(1)); ?> <?php echo e(__('Wallet Money')); ?></span>
                                    <span class="d-block sub-title"><?php echo e(__('Exchange Rate')); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="card no-border mt-2">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6"><?php echo e(__('Point Earning history')); ?></h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(__('Date')); ?></th>
                                            <th><?php echo e(__('Points')); ?></th>
                                            <th><?php echo e(__('Converted')); ?></th>
                                            <th><?php echo e(__('Action')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($club_points) > 0): ?>
                                            <?php $__currentLoopData = $club_points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $club_point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key+1); ?></td>
                                                    <td><?php echo e(date('d-m-Y', strtotime($club_point->created_at))); ?></td>
                                                    <td><?php echo e($club_point->points); ?> <?php echo e(__(' pts')); ?></td>
                                                    <td>
                                                        <?php if($club_point->convert_status == 1): ?>
                                                            <span class="ml-2" style="color:green"><strong><?php echo e(__('Yes')); ?></strong></span>
                                                        <?php else: ?>
                                                            <span class="ml-2" style="color:indigo"><strong><?php echo e(__('No')); ?></strong></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($club_point->convert_status == 0): ?>
                                                            <button onclick="convert_point(<?php echo e($club_point->id); ?>)" class="btn btn-sm  btn-styled btn-base-1 btn-primary"><?php echo e(__('Convert Now')); ?></button>
                                                        <?php else: ?>
                                                            <span class="badge badge-inline badge-success" style="color:white"><strong><?php echo e(__('Done')); ?></strong></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                <span class="d-block"><?php echo e(__('No history found.')); ?></span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                <?php echo e($club_points->links()); ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function convert_point(el)
        {
            $.post('<?php echo e(route('convert_point_into_wallet')); ?>',{_token:'<?php echo e(csrf_token()); ?>', el:el}, function(data){
                if (data == 1) {
                    location.reload();
                    showFrontendAlert('success', 'Convert has been done successfully Check your Wallets');
                }
                else {
                    showFrontendAlert('danger', 'Something went wrong');
                }
    		});
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/club_points/frontend/index.blade.php ENDPATH**/ ?>