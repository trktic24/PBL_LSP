<?php $__env->startSection('title', 'Mitra Kami'); ?>

<?php $__env->startSection('content'); ?>
    <section id="mitra" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-4 text-center font-poppins">Mitra Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mitras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mitra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($mitra->url); ?>" target="_blank" class="block group">
                        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full transition hover:-translate-y-1 hover:shadow-xl duration-300 cursor-pointer">
                            
                            <div class="mb-6">
                                    <?php
                                        // Standardized Image Logic
                                        $imgSrc = $mitra->logo ? asset('storage/' . $mitra->logo) : asset('images/default_pic.jpeg');
                                    ?>
                                    <img src="<?php echo e($imgSrc); ?>" 
                                         alt="<?php echo e($mitra->nama_mitra); ?>" 
                                         class="w-32 h-32 object-contain rounded shadow-sm bg-white group-hover:opacity-90 transition"
                                         onerror="this.onerror=null;this.src='<?php echo e(asset('images/default_pic.jpeg')); ?>';">
                               
                            </div>

                            <h5 class="text-xl font-bold text-center font-poppins mb-2 group-hover:text-blue-600 transition">
                                <?php echo e($mitra->nama_mitra); ?>

                            </h5>
                            
                            <span class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-external-link-alt"></i> Kunjungi Website
                            </span>

                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-1 md:col-span-3 text-center py-10">
                        <div class="text-gray-400 text-xl">Belum ada mitra.</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-profil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/landing_page/page_profil/mitra.blade.php ENDPATH**/ ?>