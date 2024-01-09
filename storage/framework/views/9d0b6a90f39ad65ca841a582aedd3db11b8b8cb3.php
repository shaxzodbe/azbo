<div class="aiz-category-menu bg-white rounded <?php if(Route::currentRouteName() == 'home'): ?> shadow-sm" <?php else: ?> shadow-lg" id="category-sidebar" <?php endif; ?>>
    <div class="p-3 bg-soft-primary d-none d-lg-block rounded-top all-category position-relative text-left">
        <span class="fw-600 fs-16 mr-3"><?php echo e(translate('Categories')); ?></span>
        <a href="<?php echo e(route('categories.all')); ?>" class="text-reset">
            <span class="d-none d-lg-inline-block"><?php echo e(translate('See All')); ?> ></span>
        </a>
    </div>
    <ul class="list-unstyled categories no-scrollbar py-2 mb-0 text-left">
        <?php $__currentLoopData = $frontCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li class="category-nav-element" data-id="<?php echo e($category->id); ?>">
                    <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="text-truncate text-reset py-2 px-3 d-block">

                        <?php $category_name = $category->category_translations->where('lang', app()->getLocale())->first();
                            $category_name = $category_name ? $category_name->name : $category->name;

                        ?>
                        <img
                            class="cat-image lazyload mr-2 opacity-60"
                            src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                            data-src="<?php echo e(uploaded_asset($category->icon)); ?>"
                            width="16"
                            alt="<?php echo e($category_name); ?>"
                            onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
                        >
                        <span class="cat-name"><?php echo e($category_name); ?> </span>
                        <!-- <span style="color: red">(<?php echo e($category->products_count); ?>)</span> -->
                    </a>

                    
                    <?php if($category->childrenCategories->sum('products_count')): ?>
                        <div class="sub-cat-menu c-scrollbar-light rounded shadow-lg p-4">
                            <div class="c-preloader text-center absolute-center">
                                <i class="las la-spinner la-spin la-3x opacity-70"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </li>


        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php /**PATH /var/www/azbo/resources/views/frontend/partials/category_menu.blade.php ENDPATH**/ ?>