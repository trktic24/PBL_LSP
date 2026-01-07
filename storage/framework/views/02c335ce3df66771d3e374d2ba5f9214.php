<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $__env->yieldPushContent('meta'); ?>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>LSP Polines - <?php echo $__env->yieldContent('title', 'Selamat Datang'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images\Logo_LSP_No_BG.png')); ?>">

    <!-- ========================================================== -->
    <!-- [DITAMBAHKAN] CSRF Token untuk request API JavaScript -->
    <!-- ========================================================== -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php echo $__env->yieldContent('styles'); ?>

    <style>
        [x-cloak] { display: none !important; }
      </style>

</head>

<body class="antialiased bg-white font-inter">

    <?php if (isset($component)) { $__componentOriginalc36ed93781e830d65912851da7f68a0f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc36ed93781e830d65912851da7f68a0f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc36ed93781e830d65912851da7f68a0f)): ?>
<?php $attributes = $__attributesOriginalc36ed93781e830d65912851da7f68a0f; ?>
<?php unset($__attributesOriginalc36ed93781e830d65912851da7f68a0f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc36ed93781e830d65912851da7f68a0f)): ?>
<?php $component = $__componentOriginalc36ed93781e830d65912851da7f68a0f; ?>
<?php unset($__componentOriginalc36ed93781e830d65912851da7f68a0f); ?>
<?php endif; ?>

    <div class="bg-white flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    
    
    
    <?php if (isset($component)) { $__componentOriginalf616a8f3c572fc16e4336d6c64a747a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf616a8f3c572fc16e4336d6c64a747a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf616a8f3c572fc16e4336d6c64a747a8)): ?>
<?php $attributes = $__attributesOriginalf616a8f3c572fc16e4336d6c64a747a8; ?>
<?php unset($__attributesOriginalf616a8f3c572fc16e4336d6c64a747a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf616a8f3c572fc16e4336d6c64a747a8)): ?>
<?php $component = $__componentOriginalf616a8f3c572fc16e4336d6c64a747a8; ?>
<?php unset($__componentOriginalf616a8f3c572fc16e4336d6c64a747a8); ?>
<?php endif; ?>

    <!-- ========================================================== -->
    <!-- [DITAMBAHKAN] Untuk memuat script khusus dari halaman (seperti tombol daftar) -->
    <!-- ========================================================== -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/layouts/app-profil.blade.php ENDPATH**/ ?>