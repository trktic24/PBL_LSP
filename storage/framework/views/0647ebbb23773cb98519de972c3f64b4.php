<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active' => 'home']));

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

foreach (array_filter((['active' => 'home']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $displayName = 'User';
    if(Auth::check()){
        $user = Auth::user();
        $roleName = $user->role->nama_role;

        if($roleName == 'asesi' && $user->asesi){
             $displayName = $user->asesi->nama_lengkap;
        } elseif($roleName == 'asesor' && $user->asesor){
             $displayName = $user->asesor->nama_lengkap;
        } elseif(($roleName == 'admin' || $roleName == 'superadmin') && $user->admin){
             $displayName = $user->admin->nama_lengkap;
        }

        if(empty($displayName) || $displayName === 'User'){
            $displayName = $user->email;
        }
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::check() && Auth::user()->role->nama_role == 'asesor' && (request()->is('asesor*') || request()->routeIs('asesor.*'))): ?>



<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<header
  x-data="{ openMenu: false, openDropdown: false }"
  class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] py-4 px-6 sm:px-12 z-50 font-[Poppins]">
  <div class="flex items-center justify-between w-full max-w-7xl mx-auto">
    
    <a href="<?php echo e(url('/')); ?>">
      <img src="<?php echo e(asset('images/Logo_LSP_No_BG.png')); ?>" alt="logo" class="w-20">
    </a>

    
    <nav
      :class="{ 'max-lg:hidden': !openMenu }"
      class="lg:flex lg:flex-1 lg:justify-center max-lg:fixed max-lg:flex max-lg:flex-col max-lg:justify-between
                 max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:h-full
                 max-lg:shadow-md max-lg:p-4 max-lg:overflow-auto z-40 mx-auto transition-all">
      
      <button
        @click="openMenu = false"
        class="lg:hidden absolute top-3 right-4 z-[60] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      
      <ul class="lg:flex lg:items-center lg:justify-center lg:gap-x-8 max-lg:space-y-4">
        
        <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
          <a href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/Logo_LSP_No_BG.png')); ?>" alt="logo" class="w-20">
          </a>
        </li>

        
        <?php
        $menus = [
        ['name' => 'Dashboard', 'href' => route('asesor.dashboard')],
        ['name' => 'Jadwal Asesmen', 'href' => route('asesor.jadwal.index')],
        ['name' => 'Profil', 'href' => route('asesor.profil')],
        ];
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
          <a href="<?php echo e($menu['href']); ?>"
            class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                  <?php echo e(request()->fullUrlIs($menu['href'] . '*')
                    ? 'text-blue-700 border-blue-600'
                    : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
            <?php echo e($menu['name']); ?>

          </a>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </ul>

      
      <div class="lg:hidden mt-auto border-t border-gray-200 pt-4 relative" x-data="{ openDropdownMobile: false }">
        <div class="flex items-center justify-between px-2">
          
          <a
            href="<?php echo e(route('asesor.profil')); ?>"
            class="flex items-center space-x-3 cursor-pointer select-none">
            <img
              src="<?php echo e(Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg')); ?>"
              onerror="this.onerror=null;this.src='<?php echo e(asset('images/placeholders/square.svg')); ?>';"
              alt="Foto Profil"
              class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
            <span class="text-gray-800 font-semibold">
              <?php echo e($displayName); ?>

            </span>
          </a>

          
          <button @click="openDropdownMobile = !openDropdownMobile" class="p-2 rounded-md focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
        </div>

        
        <div
          x-show="openDropdownMobile"
          @click.away="openDropdownMobile = false"
          x-transition.opacity.scale.95
          class="absolute right-4 bottom-14 w-40 bg-white rounded-md shadow-lg border border-gray-200 z-50">
          <form action="<?php echo e(route('logout')); ?>" method="POST" class="px-2 py-2">
            <?php echo csrf_field(); ?>
            <button
              type="submit"
              class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
              Log Out
            </button>
          </form>
        </div>
      </div>
    </nav>

    
    <div class="hidden lg:flex items-center mr-4">
      <?php if (isset($component)) { $__componentOriginal44628068cd82785039072947380a50fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44628068cd82785039072947380a50fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Tombol.notification_bell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Tombol.notification_bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44628068cd82785039072947380a50fc)): ?>
<?php $attributes = $__attributesOriginal44628068cd82785039072947380a50fc; ?>
<?php unset($__attributesOriginal44628068cd82785039072947380a50fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44628068cd82785039072947380a50fc)): ?>
<?php $component = $__componentOriginal44628068cd82785039072947380a50fc; ?>
<?php unset($__componentOriginal44628068cd82785039072947380a50fc); ?>
<?php endif; ?>
    </div>

    
    <div
      x-data="{ openDropdown: false }"
      class="hidden lg:flex items-center space-x-3 cursor-pointer select-none relative">
      
      <a href="<?php echo e(route('asesor.profil')); ?>" class="flex items-center space-x-3">
        <span class="text-gray-800 font-semibold">
          <?php echo e($displayName); ?>

        </span>
        <img
          src="<?php echo e(Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg')); ?>"
          onerror="this.onerror=null;this.src='<?php echo e(asset('images/placeholders/square.svg')); ?>';"
          alt="Foto Profil"
          class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
      </a>

      
      <button @click="openDropdown = !openDropdown" class="focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      
      <div
        x-show="openDropdown"
        @click.away="openDropdown = false"
        x-transition.opacity.scale.80
        class="absolute right-0 top-[60px] w-40 bg-white rounded-md shadow-lg border border-gray-200 z-50">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <button
            type="submit"
            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
            Log Out
          </button>
        </form>
      </div>
    </div>

    
    <button @click="openMenu = true" class="flex ml-2 lg:hidden">
      <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M3 5h14M3 10h14M3 15h14"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"></path>
      </svg>
    </button>

  </div>
</header>

<div class="h-[80px]"></div>

<?php else: ?>
    
    
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <header
      x-data="{ openMenu:false, openInfo:false, openProfil:false, openUser:false }"
      class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] py-4 px-6 sm:px-12 z-[999] font-[Poppins]"
    >
      <div class="flex items-center justify-between w-full max-w-7xl mx-auto">
        
        <a href="<?php echo e(url('/')); ?>">
          <img src="<?php echo e(asset('images/Logo LSP No BG.png')); ?>" alt="logo" class="w-20">
        </a>

        
        <div
          x-show="openMenu"
          x-transition.opacity
          @click="openMenu = false"
          class="lg:hidden fixed inset-0 bg-black/50 z-40"
          x-cloak
        ></div>

        
        <nav
          :class="{'max-lg:translate-x-0': openMenu, 'max-lg:-translate-x-full': !openMenu}"
          class="lg:block max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px]
                 max-lg:top-0 max-lg:left-0 max-lg:h-full max-lg:shadow-md
                 z-50 mx-auto transition-transform duration-300"
          x-cloak
        >
          
          <button
            @click="openMenu = false"
            class="lg:hidden absolute top-3 right-4 z-[60] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="h-full max-lg:overflow-y-auto lg:overflow-visible p-4">
              <ul class="lg:flex lg:items-center lg:justify-center lg:gap-x-8 max-lg:space-y-4">

            
            <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
              <a href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('images/Logo LSP No BG.png')); ?>" alt="logo" class="w-20">
              </a>
            </li>

            
            <li>
              <a href="<?php echo e(url('/')); ?>"
                class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                <?php echo e(request()->is('/') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                Home
              </a>
            </li>

            <li>
              <a href="<?php echo e(url('/jadwal')); ?>"
                class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                <?php echo e(request()->is('jadwal') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                Jadwal Asesmen
              </a>
            </li>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
              <li>
                <a href="<?php echo e(url('/login')); ?>"
                  class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                  <?php echo e(request()->is('skema') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                  Sertifikasi
                </a>
              </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role->nama_role == 'asesi'): ?>
                <li>
                  <a href="<?php echo e(route('asesi.riwayat.index')); ?>"
                    class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                    <?php echo e(request()->routeIs('asesi.riwayat.index') || request()->is('asesi*') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                    Sertifikasi
                  </a>
                </li>
              <?php elseif(auth()->user()->role->nama_role == 'admin' || auth()->user()->role->nama_role == 'superadmin'): ?>
                <li>
                  <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                    <?php echo e(request()->routeIs('admin.dashboard') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                    Dashboard
                  </a>
                </li>
              <?php else: ?>
                <li>
                  <a href="<?php echo e(route('asesor.dashboard')); ?>"
                    class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
                    <?php echo e(request()->routeIs('asesor.dashboard') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
                    Dashboard
                  </a>
                </li>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <li class="relative px-2 py-2">
              <button
                @click="openInfo = !openInfo; openProfil = false"
                class="inline-flex items-center gap-1 text-slate-900 font-medium text-[15px] hover:text-blue-700 focus:outline-none"
              >
                <span>Info</span>
                <svg class="w-2 h-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
              </button>

              <ul
                x-show="openInfo"
                @click.away="openInfo = false"
                x-transition.origin-top.opacity.scale.90
                class="absolute bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50"
              >
                <li><a href="<?php echo e(url('/alur-sertifikasi')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Alur Proses</a></li>
                <li><a href="<?php echo e(url('/daftar-asesor')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar Asesor</a></li>
                <li><a href="<?php echo e(url('/info-tuk')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">TUK</a></li>
              </ul>
            </li>

            
            <li class="relative px-2 py-2">
              <button
                @click="openProfil = !openProfil; openInfo = false"
                class="inline-flex items-center gap-1 text-slate-900 font-medium text-[15px] hover:text-blue-700 focus:outline-none"
              >
                <span>Profil</span>
                <svg class="w-2 h-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
              </button>

              <ul
                x-show="openProfil"
                @click.away="openProfil = false"
                x-transition.origin-top.opacity.scale.90
                class="absolute bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50"
              >
                <li><a href="<?php echo e(url('/visimisi')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Visi & Misi</a></li>
                <li><a href="<?php echo e(url('/struktur')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Struktur</a></li>
                <li><a href="<?php echo e(url('/mitra')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mitra</a></li>
              </ul>
            </li>

            
            <li class="lg:hidden border-t border-gray-200 pt-4 mt-4 px-2 space-y-2">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div class="mb-3 px-2">
                  <p class="font-semibold text-gray-900 truncate"><?php echo e($displayName); ?></p>
                  <p class="text-sm text-gray-500 truncate"><?php echo e(Auth::user()->email ?? 'user@email.com'); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role->nama_role == 'admin' || Auth::user()->role->nama_role == 'superadmin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Dashboard</a>
                <?php elseif(Auth::user()->role->nama_role != 'asesi'): ?>
                    <a href="<?php echo e(route('asesor.dashboard')); ?>" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Dashboard</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php
                    $profileUrl = '#';
                    if(Auth::check()){
                         $role = Auth::user()->role->nama_role;
                         if($role == 'admin' || $role == 'superadmin') $profileUrl = route('admin.profile.edit');
                         elseif($role == 'asesi') $profileUrl = route('asesi.profile.edit');
                         elseif($role == 'asesor') $profileUrl = route('asesor.profil');
                    }
                ?>
                <a href="<?php echo e($profileUrl); ?>" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Profil Saya</a>

                <form action="<?php echo e(route('logout')); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="block w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-md">
                    Logout
                  </button>
                </form>
              <?php else: ?>
                <a href="<?php echo e(url('/login')); ?>" class="block w-full text-center px-3 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md font-medium">
                  Masuk
                </a>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </li>
          </ul>
          </div>
        </nav>

        
        <div class="flex items-center">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <div class="relative">
              <button
                @click="openUser = !openUser"
                class="flex items-center gap-2 rounded-full py-1 pl-2 pr-3 transition-colors duration-200 hover:bg-gray-100 focus:outline-none"
              >
                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-blue-600 text-white font-medium text-xs">
                  <?php
                    $nama = $displayName;
                    $words = explode(' ', $nama);
                    $initials = count($words) >= 2
                      ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                      : strtoupper(substr($nama, 0, 2));
                  ?>
                  <?php echo e($initials); ?>

                </span>
                <span class="font-medium text-[15px] text-slate-900 hidden sm:block">
                  <?php echo e($displayName); ?>

                </span>
                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
              </button>

              <div
                x-show="openUser"
                @click.away="openUser = false"
                x-transition.origin-top.opacity.scale.90
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 shadow-lg rounded-md z-50 overflow-hidden"
              >
                <div class="px-4 py-3 border-b border-gray-200">
                  <p class="text-sm font-semibold text-gray-900 truncate"><?php echo e($displayName); ?></p>
                  <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email ?? 'user@email.com'); ?></p>
                </div>
                <div class="py-1">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role->nama_role == 'admin' || Auth::user()->role->nama_role == 'superadmin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                  <?php elseif(Auth::user()->role->nama_role != 'asesi'): ?>
                    <a href="<?php echo e(route('asesor.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  <?php
                    $profileUrlDesktop = '#';
                    if(Auth::check()){
                         $role = Auth::user()->role->nama_role;
                         if($role == 'admin' || $role == 'superadmin') $profileUrlDesktop = route('admin.profile.edit');
                         elseif($role == 'asesi') $profileUrlDesktop = route('asesi.profile.edit');
                         elseif($role == 'asesor') $profileUrlDesktop = route('asesor.profil');
                    }
                  ?>
                  <a href="<?php echo e($profileUrlDesktop); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                </div>
                <div class="py-1 border-t border-gray-200">
                  <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-b-md">
                      Logout
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php else: ?>
            <a href="<?php echo e(url('/login')); ?>"
              class="font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
              <?php echo e(request()->is('login') ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600'); ?>">
        Masuk
      </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      
      <button @click="openMenu = true" class="flex ml-2 lg:hidden">
        <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
      </button>
    </div>
  </div>
</header>

<div class="h-[80px]"></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\Kuliah\SMT 3\Internal\Web Dinamis\Xampp\htdocs\PBL_LSP\resources\views/components/navbar/navbar.blade.php ENDPATH**/ ?>