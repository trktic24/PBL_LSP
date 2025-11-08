<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Schedule | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    .btn-tambah {
      background-image: linear-gradient(to right, #2563EB, #1D4ED8);
      @apply shadow-md transition duration-200 ease-in-out;
    }
    .btn-tambah:hover {
      background-image: linear-gradient(to right, #1D4ED8, #1E3A8A);
      @apply shadow-lg;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-6 sm:p-10">

        <div class="grid grid-cols-3 items-center mb-8 sm:mb-10">
          <a href="{{ route('master_schedule') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium justify-self-start">
            <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center">ADD SCHEDULE</h1>
          <div class="justify-self-end"></div>
        </div>

        @if ($errors->any())
          <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg">
            <strong>Terdapat kesalahan:</strong>
            <ul class="list-disc pl-5 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        
        <form action="{{ route('add_schedule.store') }}" method="POST" class="space-y-6">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="id_skema" class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
              <div class="relative w-full">
                <select id="id_skema" name="id_skema" required 
                        {{-- 2. Class 'appearance-none pr-10' --}}
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10">
                  <option value="">Pilih Skema</option>
                  @foreach($skemas as $skema)
                    <option value="{{ $skema->id_skema }}" {{ old('id_skema') == $skema->id_skema ? 'selected' : '' }}>
                      {{ $skema->nama_skema }} ({{ $skema->kode_unit }})
                    </option>
                  @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                  <i class="fas fa-caret-down text-gray-500"></i>
                </div>
              </div>
            </div>
            <div>
              <label for="id_asesor" class="block text-sm font-medium text-gray-700 mb-2">Asesor <span class="text-red-500">*</span></label>
              <div class="relative w-full">
                <select id="id_asesor" name="id_asesor" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10">
                  <option value="">Pilih Asesor</option>
                  @foreach($asesors as $asesor)
                    <option value="{{ $asesor->id_asesor }}" {{ old('id_asesor') == $asesor->id_asesor ? 'selected' : '' }}>
                      {{ $asesor->nama_lengkap }}
                    </option>
                  @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                  <i class="fas fa-caret-down text-gray-500"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="id_tuk" class="block text-sm font-medium text-gray-700 mb-2">Tempat Uji Kompetensi (TUK) <span class="text-red-500">*</span></label>
              <div class="relative w-full">
                <select id="id_tuk" name="id_tuk" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10">
                  <option value="">Pilih TUK</option>
                  @foreach($tuks as $tuk)
                    <option value="{{ $tuk->id_tuk }}" {{ old('id_tuk') == $tuk->id_tuk ? 'selected' : '' }}>
                      {{ $tuk->nama_lokasi }}
                    </option>
                  @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                  <i class="fas fa-caret-down text-gray-500"></i>
                </div>
              </div>
            </div>
            <div>
              <label for="id_jenis_tuk" class="block text-sm font-medium text-gray-700 mb-2">Jenis TUK <span class="text-red-500">*</span></label>
              <div class="relative w-full">
                <select id="id_jenis_tuk" name="id_jenis_tuk" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10">
                  <option value="">Pilih Jenis TUK</option>
                  @foreach($jenisTuks as $jenis)
                    <option value="{{ $jenis->id_jenis_tuk }}" {{ old('id_jenis_tuk') == $jenis->id_jenis_tuk ? 'selected' : '' }}>
                      {{ $jenis->jenis_tuk }}
                    </option>
                  @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                  <i class="fas fa-caret-down text-gray-500"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label for="kuota_maksimal" class="block text-sm font-medium text-gray-700 mb-2">Kuota Maksimal <span class="text-red-500">*</span></label>
              <input type="number" id="kuota_maksimal" name="kuota_maksimal" value="{{ old('kuota_maksimal') }}" required min="1" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 20">
            </div>
            <div>
              <label for="kuota_minimal" class="block text-sm font-medium text-gray-700 mb-2">Kuota Minimal</label>
              <input type="number" id="kuota_minimal" name="kuota_minimal" value="{{ old('kuota_minimal', 15) }}" min="1" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 15">
            </div>
            <div>
              <label for="sesi" class="block text-sm font-medium text-gray-700 mb-2">Sesi <span class="text-red-500">*</span></label>
              <input type="number" id="sesi" name="sesi" value="{{ old('sesi') }}" required min="1" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 1">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tgl Mulai Pendaftaran <span class="text-red-500">*</span></label>
              <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tgl Selesai Pendaftaran <span class="text-red-500">*</span></label>
              <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="tanggal_pelaksanaan" class="block text-sm font-medium text-gray-700 mb-2">Tgl Pelaksanaan <span class="text-red-500">*</span></label>
              <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
              <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
          </div>

          <div>
            <label for="Status_jadwal" class="block text-sm font-medium text-gray-700 mb-2">Status Jadwal <span class="text-red-500">*</span></label>
            <div class="relative w-full">
              <select id="Status_jadwal" name="Status_jadwal" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white appearance-none pr-10">
                <option value="Terjadwal" {{ old('Status_jadwal', 'Terjadwal') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="Selesai" {{ old('Status_jadwal') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ old('Status_jadwal') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                <i class="fas fa-caret-down text-gray-500"></i>
              </div>
            </div>
          </div>

          <div class="pt-4 flex justify-center">
            <button type="submit" class="btn-tambah w-full py-3 text-white font-semibold rounded-lg shadow-md">
              Tambah
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>