<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesi | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }
    .appearance-none { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-between mb-10 relative">
          <a href="{{ route('master_asesi') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back
          </a> 
          <h1 class="text-3xl font-bold text-gray-900 text-center absolute left-1/2 -translate-x-1/2">
            ADD ASESI
          </h1>
          <div class="w-[80px]"></div> 
        </div>

        @if ($errors->any())
          <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg">
            <strong>Terdapat kesalahan:</strong>
            <ul class="list-disc pl-5 mt-2 text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('add_asesi.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
          @csrf
          
          <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan E-mail untuk login" value="{{ old('email') }}" required>
              </div>
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Minimal 8 karakter" required>
              </div>
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Ulangi password" required>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Data Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nama Lengkap" value="{{ old('nama_lengkap') }}" required>
              </div>
              <div>
                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                <input type="text" id="nik" name="nik" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" required>
              </div>
              <div>
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Tempat Lahir" value="{{ old('tempat_lahir') }}" required>
              </div>
              <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700" value="{{ old('tanggal_lahir') }}" required>
              </div>
              <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                <div class="relative w-full">
                  <select id="jenis_kelamin" name="jenis_kelamin" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                    <i class="fas fa-caret-down text-gray-500"></i>
                  </div>
                </div>
              </div>
              <div>
                <label for="kebangsaan" class="block text-sm font-medium text-gray-700 mb-1">Kebangsaan</label>
                <input type="text" id="kebangsaan" name="kebangsaan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: Indonesia" value="{{ old('kebangsaan') }}">
              </div>
              <div>
                <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi Pendidikan <span class="text-red-500">*</span></label>
                <input type="text" id="pendidikan" name="pendidikan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Pendidikan Terakhir" value="{{ old('pendidikan') }}" required>
              </div>
              <div>
                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan (Umum) <span class="text-red-500">*</span></label>
                <input type="text" id="pekerjaan" name="pekerjaan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Pekerjaan Saat Ini" value="{{ old('pekerjaan') }}" required>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Alamat & Kontak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                <input type="tel" id="nomor_hp" name="nomor_hp" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 08123456789" value="{{ old('nomor_hp') }}" required>
              </div>
              <div>
                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode POS</label>
                <input type="text" id="kode_pos" name="kode_pos" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 50277" value="{{ old('kode_pos') }}">
              </div>
              <div>
                <label for="kabupaten_kota" class="block text-sm font-medium text-gray-700 mb-1">Kabupaten / Kota <span class="text-red-500">*</span></label>
                <input type="text" id="kabupaten_kota" name="kabupaten_kota" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: Kota Semarang" value="{{ old('kabupaten_kota') }}" required>
              </div>
              <div>
                <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                <input type="text" id="provinsi" name="provinsi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: Jawa Tengah" value="{{ old('provinsi') }}" required>
              </div>
              <div class="md:col-span-2">
                <label for="alamat_rumah" class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah <span class="text-red-500">*</span></label>
                <textarea id="alamat_rumah" name="alamat_rumah" rows="3" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan alamat lengkap" required>{{ old('alamat_rumah') }}</textarea>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Data Pekerjaan Sekarang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="nama_institusi_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Nama Institusi/Perusahaan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_institusi_pekerjaan" name="nama_institusi_pekerjaan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: PT. Teknologi Maju" value="{{ old('nama_institusi_pekerjaan') }}" required>
              </div>
              <div>
                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                <input type="text" id="jabatan" name="jabatan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: Staff IT" value="{{ old('jabatan') }}" required>
              </div>
              <div>
                <label for="no_telepon_institusi" class="block text-sm font-medium text-gray-700 mb-1">No. Telp Institusi <span class="text-red-500">*</span></label>
                <input type="tel" id="no_telepon_institusi" name="no_telepon_institusi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 024-123456" value="{{ old('no_telepon_institusi') }}" required>
              </div>
              <div>
                <label for="kode_pos_institusi" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos Institusi</label>
                <input type="text" id="kode_pos_institusi" name="kode_pos_institusi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 50123" value="{{ old('kode_pos_institusi') }}">
              </div>
              <div class="md:col-span-2">
                <label for="alamat_institusi" class="block text-sm font-medium text-gray-700 mb-1">Alamat Institusi <span class="text-red-500">*</span></label>
                <textarea id="alamat_institusi" name="alamat_institusi" rows="3" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan alamat lengkap institusi" required>{{ old('alamat_institusi') }}</textarea>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Tanda Tangan</h3>
            <div x-data="{ fileName: '' }">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                File Tanda Tangan (Opsional, PNG/JPG)
              </label>
              <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                <i class="fas fa-upload text-gray-500 mr-3"></i>
                <span x-text="fileName || 'Pilih file...'" class="text-sm text-gray-600"></span>
                <input type="file" id="tanda_tangan" name="tanda_tangan" @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" class="opacity-0 absolute w-0 h-0" accept="image/png, image/jpeg" />
              </label>
            </div>
          </section>

          <div class="flex justify-end items-center pt-6 border-t mt-10">
            <button type="submit" class="w-full px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition">
              Tambah
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>