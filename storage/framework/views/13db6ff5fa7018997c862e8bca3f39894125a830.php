<div class="card-columns">

    <?php
        $ids = \App\Utility\CategoryUtility::get_immediate_children_ids($category->id);
        $categories = \App\Category::whereIn('id', $ids)
            ->with(['childrenCategories' => function ($query) {
                $query->withCount('products')
                    ->with('category_translations');
            }, 'childrenCategories.childrenCategories' => function ($query) {
                $query->withCount('products')->with('category_translations');
            }, 'category_translations'])
            ->withCount('products')
            ->get();

        foreach ($categories as $category) {
            foreach($category->childrenCategories as $cat) {
                $cat->products_count += $cat->childrenCategories->sum('products_count');
            }
            $category->products_count += $category->childrenCategories->sum('products_count');
        }
    ?>

    
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($category->products_count): ?>
        <div class="card shadow-none border-0">
            <ul class="list-unstyled mb-3">
                <li class="fw-600 border-bottom pb-2 mb-3">
                    <?php
                        $tr = $category->category_translations->where('lang', app()->getLocale())->first();
                        $category_name = $tr ? $tr->name : $category->name;
                    ?>
                    <a class="text-reset" href="<?php echo e(route('products.category', $category->slug)); ?>"> <?php echo e($category_name); ?> <!-- (<?php echo e($category->products_count); ?>) --> </a>
                </li>

                <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($cat->products_count && $cat->products->count()): ?>
                        <li class="mb-2">
                            <a class="text-reset" href="<?php echo e(route('products.category',$cat->slug)); ?>"><?php echo e($cat->getTranslation('name')); ?> <!-- (<?php echo e($cat->products_count); ?>) --> </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /var/www/azbo/resources/views/frontend/partials/category_elements.blade.php ENDPATH**/ ?>