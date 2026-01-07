<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'button']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'button']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button <?php echo e($attributes->merge([
    'type' => $type,
    'class' => 'bg-[#0960BD] hover:bg-[#0041A8] text-white font-medium px-6 py-2.5 rounded-[28px] shadow-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200 hover:-translate-y-[1px]'
])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/components/login-button-biru.blade.php ENDPATH**/ ?>