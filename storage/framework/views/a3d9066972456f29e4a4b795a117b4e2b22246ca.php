

<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3"><?php echo e(translate('All Customers')); ?></h1>
	</div>
</div>


<div class="card">
    <div class="card-header d-block d-lg-flex">
        <h5 class="mb-0 h6"><?php echo e(translate('Customers')); ?></h5>
        <div class="">
            <form class="" id="sort_customers" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type email or name & Enter')); ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th><?php echo e(translate('Name')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Email Address')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Phone')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Package')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Wallet Balance')); ?></th>
                    <th><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($customer->user != null): ?>
                        <tr>
                            <td><?php echo e(($key+1) + ($customers->currentPage() - 1)*$customers->perPage()); ?></td>
                            <td><?php if($customer->user->banned == 1): ?> <i class="fa fa-ban text-danger" aria-hidden="true"></i> <?php endif; ?> <?php echo e($customer->user->name); ?></td>
                            <td><?php echo e($customer->user->email); ?></td>
                            <td><?php echo e($customer->user->phone); ?></td>
                            <td>
                                <?php if($customer->user->customer_package != null): ?>
                                    <?php echo e($customer->user->customer_package->getTranslation('name')); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e(single_price($customer->user->balance)); ?></td>
                            <td class="text-right">
                               <a href="<?php echo e(route('customers.login', encrypt($customer->id))); ?>" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="<?php echo e(translate('Log in as this Customer')); ?>">
                                   <i class="las la-edit"></i>
                               </a>
                               <?php if($customer->user->banned != 1): ?>
                                   <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirm_ban('<?php echo e(route('customers.ban', $customer->id)); ?>');" title="<?php echo e(translate('Ban this Customer')); ?>">
                                       <i class="las la-user-slash"></i>
                                   </a>
                               <?php else: ?>
                                   <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_unban('<?php echo e(route('customers.ban', $customer->id)); ?>');" title="<?php echo e(translate('Unban this Customer')); ?>">
                                       <i class="las la-user-check"></i>
                                   </a>
                               <?php endif; ?>
                               <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('customers.destroy', $customer->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                   <i class="las la-trash"></i>
                               </a>
                           </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($customers->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(translate('Do you really want to ban this Customer?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a type="button" id="confirmation" class="btn btn-primary"><?php echo e(translate('Proceed!')); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(translate('Do you really want to unban this Customer?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a type="button" id="confirmationunban" class="btn btn-primary"><?php echo e(translate('Proceed!')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function sort_customers(el){
            $('#sort_customers').submit();
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/customer/customers/index.blade.php ENDPATH**/ ?>