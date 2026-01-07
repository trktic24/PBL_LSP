<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Struktur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
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

<main class="flex justify-center pt-10">
<div class="bg-white p-8 rounded shadow w-full max-w-lg">

<form action="<?php echo e(route('admin.add_struktur.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
    <?php echo csrf_field(); ?>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Posisi / Jabatan</label>
        <select name="urutan" id="selectPosisi" class="border p-2 w-full rounded bg-white" required onchange="autoFillJabatan()">
            <option value="" disabled selected>-- Pilih Posisi dalam Struktur --</option>
            
            <optgroup label="Level 1: Pimpinan Tertinggi">
                <option value="1" data-text="Dewan Pengarah">1. Dewan Pengarah</option>
            </optgroup>

            <optgroup label="Level 2: Ketua LSP & Komite">
                <option value="2" data-text="Ketua LSP">2. Ketua LSP</option>
                <option value="7" data-text="Ketua Komite Skema">7. Ketua Komite Skema</option>
                <option value="8" data-text="Anggota Komite Skema">8. Anggota Komite Skema</option>
            </optgroup>

            <optgroup label="Level 3: Bidang - Bidang">
                <option value="3" data-text="Kepala Bagian Administrasi">3. Kabag Administrasi</option>
                <option value="4" data-text="Anggota Bagian Administrasi">4. Anggota Administrasi</option>
                
                <option value="5" data-text="Kepala Bagian Sertifikasi">5. Kabag Sertifikasi</option>
                <option value="6" data-text="Anggota Bagian Sertifikasi">6. Anggota Sertifikasi</option>
                
                <option value="9" data-text="Kepala Bagian Kerjasama">9. Kabag Kerjasama</option>
                
                <option value="10" data-text="Kepala Bagian Manajemen Mutu">10. Kabag Manajemen Mutu</option>
                <option value="11" data-text="Anggota Bagian Manajemen Mutu">11. Anggota Manajemen Mutu</option>
            </optgroup>
        </select>
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input name="nama" placeholder="Contoh: Dr. Budi Santoso, M.Kom" class="border p-2 w-full rounded" required>
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Jabatan (Tampil di Web)</label>
        <input name="jabatan" id="inputJabatan" placeholder="Jabatan" class="border p-2 w-full rounded" required>
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
        <input type="file" name="gambar" class="border p-2 w-full rounded bg-white">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700 font-bold mt-4">Simpan Data</button>
</form>


<script>
    function autoFillJabatan() {
        var select = document.getElementById('selectPosisi');
        var input = document.getElementById('inputJabatan');
        // Ambil text dari atribut data-text pada option yang dipilih
        var selectedText = select.options[select.selectedIndex].getAttribute('data-text');
        
        if(selectedText) {
            input.value = selectedText;
        }
    }
</script>

</div>
</main>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/Admin/master/struktur/add_struktur.blade.php ENDPATH**/ ?>