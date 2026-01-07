<a <?php echo e($attributes->merge([
    'class' => 'w-full bg-white border border-gray-300 text-gray-700 font-medium px-6 py-3 rounded-[32px] shadow-sm flex items-center justify-center gap-3 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-200 transition duration-200 hover:-translate-y-[1px]'
])); ?>>
    <img src="<?php echo e(asset('images\google-icon-logo-svgrepo-com.svg')); ?>" alt="Google" class="w-5 h-5">
    <span><?php echo e($slot); ?></span>
</a>
<?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/components/login-button-google.blade.php ENDPATH**/ ?>