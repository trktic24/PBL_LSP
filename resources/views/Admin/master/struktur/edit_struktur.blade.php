<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Struktur | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
<div class="min-h-screen flex flex-col">
    <x-navbar.navbar-admin/>

    <main class="flex justify-center items-start pt-10 px-4">
        <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Struktur Organisasi</h1>
            </div>

            {{-- Pesan Error Upload --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Gagal Update!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.update_struktur', $organisasi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                    <select name="urutan" id="selectPosisi" class="w-full border border-gray-300 rounded bg-white p-2" required onchange="autoFillJabatan()">
                        <optgroup label="Level 1: Pimpinan Tertinggi">
                            <option value="1" data-text="Dewan Pengarah" {{ $organisasi->urutan == 1 ? 'selected' : '' }}>1. Dewan Pengarah</option>
                        </optgroup>
                        <optgroup label="Level 2: Ketua LSP & Komite">
                            <option value="2" data-text="Ketua LSP" {{ $organisasi->urutan == 2 ? 'selected' : '' }}>2. Ketua LSP</option>
                            <option value="7" data-text="Ketua Komite Skema" {{ $organisasi->urutan == 7 ? 'selected' : '' }}>7. Ketua Komite Skema</option>
                            <option value="8" data-text="Anggota Komite Skema" {{ $organisasi->urutan == 8 ? 'selected' : '' }}>8. Anggota Komite Skema</option>
                        </optgroup>
                        <optgroup label="Level 3: Bidang - Bidang">
                            <option value="3" data-text="Kepala Bagian Administrasi" {{ $organisasi->urutan == 3 ? 'selected' : '' }}>3. Kabag Administrasi</option>
                            <option value="4" data-text="Anggota Bagian Administrasi" {{ $organisasi->urutan == 4 ? 'selected' : '' }}>4. Anggota Administrasi</option>
                            <option value="5" data-text="Kepala Bagian Sertifikasi" {{ $organisasi->urutan == 5 ? 'selected' : '' }}>5. Kabag Sertifikasi</option>
                            <option value="6" data-text="Anggota Bagian Sertifikasi" {{ $organisasi->urutan == 6 ? 'selected' : '' }}>6. Anggota Sertifikasi</option>
                            <option value="9" data-text="Kepala Bagian Kerjasama" {{ $organisasi->urutan == 9 ? 'selected' : '' }}>9. Kabag Kerjasama</option>
                            <option value="10" data-text="Kepala Bagian Manajemen Mutu" {{ $organisasi->urutan == 10 ? 'selected' : '' }}>10. Kabag Manajemen Mutu</option>
                            <option value="11" data-text="Anggota Bagian Manajemen Mutu" {{ $organisasi->urutan == 11 ? 'selected' : '' }}>11. Anggota Manajemen Mutu</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $organisasi->nama) }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Jabatan</label>
                    <input type="text" name="jabatan" id="inputJabatan" value="{{ old('jabatan', $organisasi->jabatan) }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    @if($organisasi->gambar)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ asset('storage/'.$organisasi->gambar) }}" class="w-20 h-20 object-cover rounded-full border shadow">
                            <p class="text-sm text-gray-500">Foto saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="gambar" class="w-full border border-gray-300 rounded p-2 bg-white">
                </div>

                <div class="flex justify-between items-center pt-6 border-t">
                    <a href="{{ route('admin.master_struktur') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Kembali</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition font-semibold">Simpan Perubahan</button>
                </div>
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