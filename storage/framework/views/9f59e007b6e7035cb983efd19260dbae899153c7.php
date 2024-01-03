

<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('All Sellers')); ?></h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="<?php echo e(route('sellers.create')); ?>" class="btn btn-circle btn-info">
				<span><?php echo e(translate('Add New Seller')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6"><?php echo e(translate('Sellers')); ?></h5>
        </div>
          <div class="col-md-3 ml-auto">
            <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_sellers()">
                <option value=""><?php echo e(translate('Filter by Approval')); ?></option>
                <option value="1"  <?php if(isset($approved)): ?> <?php if($approved == 'paid'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Approved')); ?></option>
                <option value="0"  <?php if(isset($approved)): ?> <?php if($approved == 'unpaid'): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(translate('Non-Approved')); ?></option>
            </select>
          </div>
          <div class="col-md-3">
            <div class="form-group mb-0">
              <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type name or email & Enter')); ?>">
            </div>
          </div>
      </div>
    </from>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th><?php echo e(translate('Name')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Phone')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Email Address')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Verification Info')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Approval')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Num. of Products')); ?></th>
                <th data-breakpoints="lg"><?php echo e(translate('Due to seller')); ?></th>
                <th width="10%"><?php echo e(translate('Options')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($seller->user != null && $seller->user->shop != null): ?>
                    <tr>
                        <td><?php echo e(($key+1) + ($sellers->currentPage() - 1)*$sellers->perPage()); ?></td>
                        <td><?php if($seller->user->banned == 1): ?> <i class="fa fa-ban text-danger" aria-hidden="true"></i> <?php endif; ?> <?php echo e($seller->user->shop->name); ?></td>
                        <td><?php echo e($seller->user->phone); ?></td>
                        <td><?php echo e($seller->user->email); ?></td>
                        <td>
                            <?php if($seller->verification_info != null): ?>
                                <a href="<?php echo e(route('sellers.show_verification_request', $seller->id)); ?>">
                                  <span class="badge badge-inline badge-info"><?php echo e(translate('Show')); ?></span>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_approved(this)" value="<?php echo e($seller->id); ?>" type="checkbox" <?php if($seller->verification_status == 1) echo "checked";?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td><?php echo e(\App\Product::where('user_id', $seller->user->id)->count()); ?></td>
                        <td>
                            <?php if($seller->admin_to_pay >= 0): ?>
                                <?php echo e(single_price($seller->admin_to_pay)); ?>

                            <?php else: ?>
                                <?php echo e(single_price(abs($seller->admin_to_pay))); ?> (Due to Admin)
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                  <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    <a href="#" onclick="show_seller_profile('<?php echo e($seller->id); ?>');"  class="dropdown-item">
                                      <?php echo e(translate('Profile')); ?>

                                    </a>
                                    <a href="<?php echo e(route('sellers.login', encrypt($seller->id))); ?>" class="dropdown-item">
                                      <?php echo e(translate('Log in as this Seller')); ?>

                                    </a>
                                    <a href="#" onclick="show_seller_payment_modal('<?php echo e($seller->id); ?>');" class="dropdown-item">
                                      <?php echo e(translate('Go to Payment')); ?>

                                    </a>
                                    <a href="<?php echo e(route('sellers.payment_history', encrypt($seller->id))); ?>" class="dropdown-item">
                                      <?php echo e(translate('Payment History')); ?>

                                    </a>
                                    <a href="<?php echo e(route('sellers.edit', encrypt($seller->id))); ?>" class="dropdown-item">
                                      <?php echo e(translate('Edit')); ?>

                                    </a>
                                    <?php if($seller->user->banned != 1): ?>
                                      <a href="#" onclick="confirm_ban('<?php echo e(route('sellers.ban', $seller->id)); ?>');" class="dropdown-item">
                                        <?php echo e(translate('Ban this seller')); ?>

                                        <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                      </a>
                                    <?php else: ?>
                                      <a href="#" onclick="confirm_unban('<?php echo e(route('sellers.ban', $seller->id)); ?>');" class="dropdown-item">
                                        <?php echo e(translate('Unban this seller')); ?>

                                        <i class="fa fa-check text-success" aria-hidden="true"></i>
                                      </a>
                                    <?php endif; ?>
                                    <a href="#" class="dropdown-item confirm-delete" data-href="<?php echo e(route('sellers.destroy', $seller->id)); ?>" class="">
                                      <?php echo e(translate('Delete')); ?>

                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
          <?php echo e($sellers->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
	<!-- Delete Modal -->
	<?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<!-- Seller Profile Modal -->
	<div class="modal fade" id="profile_modal">
		<div class="modal-dialog">
			<div class="modal-content" id="profile-modal-content">

			</div>
		</div>
	</div>

	<!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	<!-- Ban Seller Modal -->
	<div class="modal fade" id="confirm-ban">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
					<button type="button" class="close" data-dismiss="modal">
					</button>
				</div>
				<div class="modal-body">
						<p><?php echo e(translate('Do you really want to ban this seller?')); ?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
					<a class="btn btn-primary" id="confirmation"><?php echo e(translate('Proceed!')); ?></a>
				</div>
			</div>
		</div>
	</div>

	<!-- Unban Seller Modal -->
	<div class="modal fade" id="confirm-unban">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
						<button type="button" class="close" data-dismiss="modal">
						</button>
					</div>
					<div class="modal-body">
							<p><?php echo e(translate('Do you really want to ban this seller?')); ?></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
						<a class="btn btn-primary" id="confirmationunban"><?php echo e(translate('Proceed!')); ?></a>
					</div>
				</div>
			</div>
		</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function show_seller_payment_modal(id){
            $.post('<?php echo e(route('sellers.payment_modal')); ?>',{_token:'<?php echo e(@csrf_token()); ?>', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_profile(id){
            $.post('<?php echo e(route('sellers.profile_modal')); ?>',{_token:'<?php echo e(@csrf_token()); ?>', id:id}, function(data){
                $('#profile_modal #profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('sellers.approved')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Approved sellers updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/sellers/index.blade.php ENDPATH**/ ?>