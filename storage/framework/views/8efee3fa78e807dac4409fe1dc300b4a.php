<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Struktur Organisasi | LSP Polines</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
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

    
    <main class="flex justify-center items-start pt-10 px-4">

        <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Struktur Organisasi</h1>
                <p class="text-sm text-gray-500">Perbarui data pengurus atau jabatan organisasi</p>
            </div>

            
            <form action="<?php echo e(route('admin.update_struktur', $organisasi->id)); ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan (Level)</label>
                    <select name="urutan" id="selectPosisi" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500 bg-white" 
                            required 
                            onchange="autoFillJabatan()">
                        
                        <option value="" disabled>-- Pilih Posisi --</option>
                        
                        <optgroup label="Level 1: Pimpinan Tertinggi">
                            <option value="1" data-text="Dewan Pengarah" <?php echo e($organisasi->urutan == 1 ? 'selected' : ''); ?>>1. Dewan Pengarah</option>
                        </optgroup>
            
                        <optgroup label="Level 2: Ketua LSP & Komite">
                            <option value="2" data-text="Ketua LSP" <?php echo e($organisasi->urutan == 2 ? 'selected' : ''); ?>>2. Ketua LSP</option>
                            <option value="7" data-text="Ketua Komite Skema" <?php echo e($organisasi->urutan == 7 ? 'selected' : ''); ?>>7. Ketua Komite Skema</option>
                            <option value="8" data-text="Anggota Komite Skema" <?php echo e($organisasi->urutan == 8 ? 'selected' : ''); ?>>8. Anggota Komite Skema</option>
                        </optgroup>
            
                        <optgroup label="Level 3: Bidang - Bidang">
                            <option value="3" data-text="Kepala Bagian Administrasi" <?php echo e($organisasi->urutan == 3 ? 'selected' : ''); ?>>3. Kabag Administrasi</option>
                            <option value="4" data-text="Anggota Bagian Administrasi" <?php echo e($organisasi->urutan == 4 ? 'selected' : ''); ?>>4. Anggota Administrasi</option>
                            
                            <option value="5" data-text="Kepala Bagian Sertifikasi" <?php echo e($organisasi->urutan == 5 ? 'selected' : ''); ?>>5. Kabag Sertifikasi</option>
                            <option value="6" data-text="Anggota Bagian Sertifikasi" <?php echo e($organisasi->urutan == 6 ? 'selected' : ''); ?>>6. Anggota Sertifikasi</option>
                            
                            <option value="9" data-text="Kepala Bagian Kerjasama" <?php echo e($organisasi->urutan == 9 ? 'selected' : ''); ?>>9. Kabag Kerjasama</option>
                            
                            <option value="10" data-text="Kepala Bagian Manajemen Mutu" <?php echo e($organisasi->urutan == 10 ? 'selected' : ''); ?>>10. Kabag Manajemen Mutu</option>
                            <option value="11" data-text="Anggota Bagian Manajemen Mutu" <?php echo e($organisasi->urutan == 11 ? 'selected' : ''); ?>>11. Anggota Manajemen Mutu</option>
                        </optgroup>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">*Memilih posisi akan otomatis mengisi kolom "Teks Jabatan" di bawah.</p>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="nama"
                           value="<?php echo e(old('nama', $organisasi->nama)); ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                           required>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Teks Jabatan (Tampil di Web)
                    </label>
                    <input type="text"
                           name="jabatan"
                           id="inputJabatan"
                           value="<?php echo e(old('jabatan', $organisasi->jabatan)); ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                           required>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Foto Profil
                    </label>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($organisasi->gambar): ?>
                        <div class="mb-3 flex items-center gap-4">
                            <img src="<?php echo e(asset('storage/'.$organisasi->gambar)); ?>"
                                 class="w-20 h-20 object-cover rounded-full border shadow">
                            <div class="text-sm text-gray-500">
                                <p>Foto saat ini.</p>
                                <p class="text-xs">(Biarkan kosong jika tidak ingin mengubah foto)</p>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <input type="file"
                           name="gambar"
                           class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                </div>

                
                <div class="flex justify-between items-center pt-6 border-t mt-6">
                    <a href="<?php echo e(route('admin.master_struktur')); ?>"
                       class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        Kembali
                    </a>

                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </main>
</div>


<script>
    function autoFillJabatan() {
        var select = document.getElementById('selectPosisi');
        var input = document.getElementById('inputJabatan');
        
        // Ambil text dari atribut data-text pada option yang dipilih
        var selectedText = select.options[select.selectedIndex].getAttribute('data-text');
        
        // Jika ada datanya, masukkan ke input text
        if(selectedText) {
            input.value = selectedText;
        }
    }
</script>

</body>
</html><?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/Admin/master/struktur/edit_struktur.blade.php ENDPATH**/ ?>