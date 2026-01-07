<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'label' => null,
    'value' => null,
    'error' => null,
    'required' => false,
    'readonly' => false,
]));

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

foreach (array_filter(([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'label' => null,
    'value' => null,
    'error' => null,
    'required' => false,
    'readonly' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="w-full">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-gray-600 mb-1">
            <?php echo e($label); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?>
                <span class="text-red-500">*</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <input
    id="<?php echo e($id); ?>"
    name="<?php echo e($name); ?>"
    type="<?php echo e($type); ?>"
    value="<?php echo e(old($name, $value)); ?>"
    <?php echo e($required ? 'required' : ''); ?>

    <?php echo e($readonly ? 'readonly' : ''); ?>

    <?php echo e($attributes->merge([
        'class' => 'mt-1 block w-full rounded-xl border bg-gray-20 ' .
           ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200') .
           ' px-3 py-2.5 text-sm focus:outline-none focus:ring-2 transition duration-200 placeholder:text-gray-400'
        ])); ?>

    x-bind:class="{
        '!border-red-500 !focus:border-red-500 !focus:ring-red-200': typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['<?php echo e($name); ?>'] && errors['<?php echo e($name); ?>'],
        '!border-gray-300 !focus:border-blue-500 !focus:ring-blue-200': typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['<?php echo e($name); ?>'] && !errors['<?php echo e($name); ?>'] && !'<?php echo e($error); ?>'
    }"
    />


    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="text-xs text-red-500 mt-1"><?php echo e($error); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <p x-show="typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['<?php echo e($name); ?>'] && errors['<?php echo e($name); ?>']"
       x-text="errors['<?php echo e($name); ?>']"
       class="text-xs text-red-500 mt-1"
       style="display: none;">
    </p>
</div>
<?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/components/login-form-input.blade.php ENDPATH**/ ?>