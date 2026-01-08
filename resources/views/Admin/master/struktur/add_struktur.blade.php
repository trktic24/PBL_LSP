<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Struktur | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex flex-col min-h-screen">
    <x-navbar.navbar-admin/>

    <main class="flex justify-center pt-10 px-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pejabat Baru</h2>

            <form action="{{ route('admin.add_struktur.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Posisi / Jabatan</label>
                    <select name="urutan" id="selectPosisi" class="border p-2 w-full rounded bg-white" required onchange="autoFillJabatan()">
                        <option value="" disabled selected>-- Pilih Posisi --</option>
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
                    <input name="nama" placeholder="Contoh: Dr. Budi Santoso" class="border p-2 w-full rounded" required>
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
        </div>
    </main>
</div>
<script>
    function autoFillJabatan() {
        var select = document.getElementById('selectPosisi');
        var input = document.getElementById('inputJabatan');
        var selectedText = select.options[select.selectedIndex].getAttribute('data-text');
        if(selectedText) input.value = selectedText;
    }
</script>
</body>
</html>