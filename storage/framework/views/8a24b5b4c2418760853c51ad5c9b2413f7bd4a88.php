<?php $__env->startSection('css'); ?>
<style>
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 135px;
    }

    .menu_category {
        display: flex;

        flex-wrap: wrap;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .menu_category_child {
        flex-basis: 16.65%;
        cursor: pointer;

        display: block;

        border: 1px solid #e8e8e8;

        /* min-width: 10rem; */

        display: flex;
        align-items: center;
        height: 4rem;
        padding-left: .8rem;
        padding-right: .8rem;
        font-size: .8rem;
        font-weight: bold;
        color: #000;
    }

  

    @media  screen and (max-width: 894px) {
        .menu_category_child {
            flex-basis: 20%;
        }
    }

    @media  screen and (max-width: 750px) {
        .menu_category_child {
            flex-basis: 25%;
        }
    }


    @media  screen and (max-width: 630px) {
        .menu_category_child {
            flex-basis: 33.2%;
        }
    }

</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4"><?php echo e(translate('All Categories')); ?></h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="<?php echo e(route('home')); ?>"><?php echo e(translate('Home')); ?></a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset"
                            href="<?php echo e(route('categories.all')); ?>">"<?php echo e(translate('All Categories')); ?>"</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="container">

</div>


<section class="mb-4">
    <div class="container" style="position: relative">
        <nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top  m-0 pt-0 pb-0" id="category_nav"
            style="padding:0; margin:0; ">
            <div class="nav menu_category bg-white shadow-sm collapse navbar-collapse" id="menu_category">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="menu_category_child" href="#cat_<?php echo e($key); ?>">
                    <img src="<?php echo e(uploaded_asset($category->icon)); ?>" alt="" style="width: 25px; height: 25px;">
                    <p style="padding-left: .625rem;">
                        <?php echo e($category->getTranslation('name')); ?>

                    </p>
                </a>
           
                <?php if($key == 10): ?>
                <a class="menu_category_child" href="#cat_<?php echo e($key+1); ?>">
                    --- ---
                    <p style="padding-left: .625rem;">
                        Other categories
                    </p>
                </a>
           
                <?php break; ?>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </nav>
        <div data-spy="scroll" data-target="#category_nav" data-offset="500">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-3 bg-white shadow-sm rounded" id="cat_<?php echo e($key); ?>">
                <div class="p-3 border-bottom fs-16 fw-600">
                    <a href="<?php echo e(route('products.category', $category->slug)); ?>"
                        class="text-reset"><?php echo e($category->getTranslation('name')); ?></a>
                </div>
                <div class="p-3 p-lg-4">
                    <div class="row">
                        <?php $__currentLoopData = \App\Utility\CategoryUtility::get_immediate_children_ids($category->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>
                        $first_level_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-6 text-left">
                            <h6 class="mb-3"><a class="text-reset fw-600 fs-14"
                                    href="<?php echo e(route('products.category', \App\Category::find($first_level_id)->slug)); ?>"><?php echo e(\App\Category::find($first_level_id)->getTranslation('name')); ?></a>
                            </h6>
                            <ul class="mb-3 list-unstyled pl-2">
                                <?php $__currentLoopData = \App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $second_level_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2">
                                    <a class="text-reset"
                                        href="<?php echo e(route('products.category', \App\Category::find($second_level_id)->slug)); ?>"><?php echo e(\App\Category::find($second_level_id)->getTranslation('name')); ?></a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    </div>
</section>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<script>
    (function () {
        var h = document.getElementsByTagName('header');
        h[0].classList.remove('sticky-top');

        var height = document.getElementById('menu_category'); 

        console.log(height);
    })();  
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/all_category.blade.php ENDPATH**/ ?>