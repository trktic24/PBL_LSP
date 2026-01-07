<?php if (isset($component)) { $__componentOriginal41d51155e84d441a6a8cd32434074bb9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41d51155e84d441a6a8cd32434074bb9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.register-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('register-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="bg-gray-100 w-full flex items-center justify-center py-5"
        x-data="{
            errors: {},
            touched: {},
            validate(field, value) {
                let error = null;
                value = value || '';
                if (field === 'email') {
                    if (!value) error = 'Email wajib diisi';
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) error = 'Format email tidak valid';
                }
                if (field === 'password') {
                    if (!value) error = 'Password wajib diisi';
                }
                this.errors[field] = error;
            },
            handleInput(e) {
                if (e.target.tagName === 'INPUT') {
                    this.validate(e.target.name, e.target.value);
                }
            },
            handleBlur(e) {
                if (e.target.tagName === 'INPUT') {
                    this.touched[e.target.name] = true;
                    this.validate(e.target.name, e.target.value);
                }
            }
        }"
        @input.capture="handleInput($event)"
        @focusout.capture="handleBlur($event)"
    >
        <div class="w-full max-w-4xl bg-white rounded-3xl p-10 md:p-12 border border-gray-300 shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                <div class="flex flex-col justify-center">
                    <div>
                        <a href="/">
                            <img src="<?php echo e(asset('images/Logo LSP No BG.png')); ?>" alt="Logo LSP Polines" class="h-20 w-auto">
                        </a>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded-md p-4" role="alert">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

                    <div class="mt-6 mb-8">
                        <h1 class="font-poppins text-2xl font-semibold text-gray-900 mb-1">Masuk ke Akun Anda</h1>
                        <p class="text-sm text-gray-500">
                            Belum punya akun?
                            <a href="<?php echo e(route('register')); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                                Daftar
                            </a>
                        </p>
                    </div>

                    <form id="login-form" method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>

                        <?php if (isset($component)) { $__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-form-input','data' => ['id' => 'email','name' => 'email','type' => 'email','label' => 'Email','error' => $errors->first('email'),'value' => old('email'),'required' => true,'autofocus' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-form-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','name' => 'email','type' => 'email','label' => 'Email','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('email')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'required' => true,'autofocus' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67)): ?>
<?php $attributes = $__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67; ?>
<?php unset($__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67)): ?>
<?php $component = $__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67; ?>
<?php unset($__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67); ?>
<?php endif; ?>
                        <div x-data="{ show: false }">
                            <?php if (isset($component)) { $__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-form-input','data' => ['id' => 'password','name' => 'password','type' => 'password','xBind:type' => 'show ? \'text\' : \'password\'','label' => 'Password','error' => $errors->first('password'),'required' => true,'autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-form-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password','name' => 'password','type' => 'password','x-bind:type' => 'show ? \'text\' : \'password\'','label' => 'Password','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('password')),'required' => true,'autocomplete' => 'current-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67)): ?>
<?php $attributes = $__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67; ?>
<?php unset($__attributesOriginalb0b5ef81ab38d656b9a975072cfdfa67); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67)): ?>
<?php $component = $__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67; ?>
<?php unset($__componentOriginalb0b5ef81ab38d656b9a975072cfdfa67); ?>
<?php endif; ?>
                            <div class="flex justify-between mt-2">
                                <div>
                                    <input id="show_password_checkbox" type="checkbox"
                                       @click="show = !show"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="show_password_checkbox" class="ml-2 text-sm text-gray-600">
                                    Tampilkan Password
                                </label>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            Lupa Password?
                                        </a>

                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                <div class="hidden md:flex items-center justify-center">
                    <img src="<?php echo e(asset('images/Ilustrasi-1.jpg')); ?>" alt="Ilustrasi Login"
                         class="max-w-[200px] mx-auto">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">

                <?php if (isset($component)) { $__componentOriginal579e381f9d1c6d4483ab8fefb7eb1fee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal579e381f9d1c6d4483ab8fefb7eb1fee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-button-biru','data' => ['type' => 'submit','form' => 'login-form','class' => 'w-full sm:w-1/2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-button-biru'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','form' => 'login-form','class' => 'w-full sm:w-1/2']); ?>
                    Masuk ke Akun
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal579e381f9d1c6d4483ab8fefb7eb1fee)): ?>
<?php $attributes = $__attributesOriginal579e381f9d1c6d4483ab8fefb7eb1fee; ?>
<?php unset($__attributesOriginal579e381f9d1c6d4483ab8fefb7eb1fee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal579e381f9d1c6d4483ab8fefb7eb1fee)): ?>
<?php $component = $__componentOriginal579e381f9d1c6d4483ab8fefb7eb1fee; ?>
<?php unset($__componentOriginal579e381f9d1c6d4483ab8fefb7eb1fee); ?>
<?php endif; ?>

                <div class="flex items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="px-3 text-sm text-gray-400 font-medium">OR</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <?php if (isset($component)) { $__componentOriginal8123a5848b1092b29621e8f2ea8c37e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8123a5848b1092b29621e8f2ea8c37e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-button-google','data' => ['href' => ''.e(route('google.login')).'','class' => ' font-poppins w-full sm:w-1/2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-button-google'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('google.login')).'','class' => ' font-poppins w-full sm:w-1/2']); ?>
                    Continue with Google
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8123a5848b1092b29621e8f2ea8c37e1)): ?>
<?php $attributes = $__attributesOriginal8123a5848b1092b29621e8f2ea8c37e1; ?>
<?php unset($__attributesOriginal8123a5848b1092b29621e8f2ea8c37e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8123a5848b1092b29621e8f2ea8c37e1)): ?>
<?php $component = $__componentOriginal8123a5848b1092b29621e8f2ea8c37e1; ?>
<?php unset($__componentOriginal8123a5848b1092b29621e8f2ea8c37e1); ?>
<?php endif; ?>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41d51155e84d441a6a8cd32434074bb9)): ?>
<?php $attributes = $__attributesOriginal41d51155e84d441a6a8cd32434074bb9; ?>
<?php unset($__attributesOriginal41d51155e84d441a6a8cd32434074bb9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41d51155e84d441a6a8cd32434074bb9)): ?>
<?php $component = $__componentOriginal41d51155e84d441a6a8cd32434074bb9; ?>
<?php unset($__componentOriginal41d51155e84d441a6a8cd32434074bb9); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/auth/login.blade.php ENDPATH**/ ?>