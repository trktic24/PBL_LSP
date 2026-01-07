<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struktur Organisasi | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100">
<div class="min-h-screen flex flex-col">

    <?php if (isset($component)) { $__componentOriginalcdcdca75794a1024cfa6b2324664d859 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdcdca75794a1024cfa6b2324664d859 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar.navbar-admin','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar.navbar-admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcdcdca75794a1024cfa6b2324664d859)): ?>
<?php $attributes = $__attributesOriginalcdcdca75794a1024cfa6b2324664d859; ?>
<?php unset($__attributesOriginalcdcdca75794a1024cfa6b2324664d859); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcdcdca75794a1024cfa6b2324664d859)): ?>
<?php $component = $__componentOriginalcdcdca75794a1024cfa6b2324664d859; ?>
<?php unset($__componentOriginalcdcdca75794a1024cfa6b2324664d859); ?>
<?php endif; ?>

    <main class="p-6">

        <h1 class="text-3xl font-bold mb-6">Struktur Organisasi</h1>

        <a href="<?php echo e(route('admin.add_struktur')); ?>"
           class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            + Tambah Struktur
        </a>

        <div class="bg-white rounded shadow p-4">
            <table class="w-full border border-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Jabatan</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
                </thead>

                <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $organisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo e($loop->iteration); ?></td>
                        <td class="border px-4 py-2"><?php echo e($org->nama); ?></td>
                        <td class="border px-4 py-2"><?php echo e($org->jabatan); ?></td>
                        <td class="border px-4 py-2 text-center">
                            <a href="<?php echo e(route('admin.edit_struktur', $org->id)); ?>" class="text-blue-600">Edit</a>
                            |
                            <form action="<?php echo e(route('admin.delete_struktur', $org->id)); ?>"
                                  method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button onclick="return confirm('Hapus?')" class="text-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Belum ada data struktur
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>

            <div class="mt-4">
                <?php echo e($organisasis->links()); ?>

            </div>
        </div>

    </main>

</div>
</body>
</html>
<?php /**PATH D:\Kuliah\SMT 3\Internal\Web Dinamis\Xampp\htdocs\PBL_LSP\resources\views/Admin/master/struktur/index.blade.php ENDPATH**/ ?>