

<?php $__env->startSection('content'); ?>

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3"><?php echo e(translate('All Color')); ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6"><?php echo e(translate('Colors')); ?></h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_colors" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search" name="search"
                                       <?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>"
                                       <?php endif; ?> placeholder="<?php echo e(translate('Type name & Enter')); ?>">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(translate('Name')); ?></th>
                            <th><?php echo e(translate('Code')); ?></th>
                            <th class="text-right"><?php echo e(translate('Options')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(($key+1) + ($colors->currentPage() - 1)*$colors->perPage()); ?></td>
                                <td><?php echo e($color->getTranslation('name')); ?></td>
                                <td>
                                    <div class="aiz-radio-inline">
                                        <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip">
                                                <span
                                                    class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                        <span class="size-30px d-inline-block rounded"
                                                              style="background: <?php echo e($color->code); ?>;"></span>
                                                    </span>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                       href="<?php echo e(route('colors.edit', ['id'=>$color->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>"
                                       title="<?php echo e(translate('Edit')); ?>">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                       data-href="<?php echo e(route('colors.destroy', $color->id)); ?>"
                                       title="<?php echo e(translate('Delete')); ?>">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        <?php echo e($colors->appends(request()->input())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6"><?php echo e(translate('Add New Color')); ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('colors.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="name"><?php echo e(translate('Color')); ?></label>
                            <input type="text" placeholder="<?php echo e(translate('Color')); ?>" name="name" class="form-control"
                                   required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code"><?php echo e(translate('Code')); ?></label>
                            <input type="color" value="#ff0000" placeholder="<?php echo e(translate('Code')); ?>" name="code" class="form-control"
                                   required>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </form>
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
        function sort_colors(el) {
            $('#sort_colors').submit();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/backend/product/colors/index.blade.php ENDPATH**/ ?>