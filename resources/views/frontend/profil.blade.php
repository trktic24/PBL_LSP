@extends('layouts.app-profil')
@section('content')

<main class="max-w-6xl mx-auto mt-8 bg-white p-8 rounded-lg shadow-md relative pb-20">

    <!-- Foto Profil -->
    <div class="flex flex-col items-center space-y-2 mb-10">
        <div class="w-32 h-32 rounded-full border-4 border-blue-500 flex items-center justify-center overflow-hidden relative group cursor-pointer">
            <img src="{{ Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg') }}"
                 alt="Foto Profil"
                 class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- DATA PROFIL -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-10 w-full">

        <div class="flex flex-col space-y-8 w-full">

            <div>
                <h2 class="text-lg font-semibold mb-3">Data Pribadi</h2>
                <div class="space-y-3">
                    <div>
                    <label for="nomor_induk" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Registrasi Asesor
                    </label>
                    <input type="text" id="no_induk" placeholder="No Registrasi Asesor"
                           value="{{ $user->asesor->nomor_regis ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>
                    <div>
                    <label for="nama_asesor" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" placeholder="Nama Lengkap"
                           value="{{ $user->asesor->nama_lengkap ?? $user->username }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>
                    <div>
                    <label for="No_kewarga" class="block text-sm font-medium text-gray-700 mb-1">
                        NIK
                    </label>
                    <input type="text" id="nik" placeholder="NIK"
                           value="{{ $user->asesor->nik ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Alamat & Kontak</h2>
                <div class="space-y-3">
                    <div>
                    <label for="alamat_rumah" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Rumah
                    </label>
                    <textarea id="alamat_rumah" placeholder="Alamat Rumah"
                              class="profile-input w-full border h-[100px] border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                              readonly>{{ $user->asesor->alamat_rumah ?? '' }}</textarea>
                    </div>
                    <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor HP
                    </label>
                    <input type="text" id="nomor_hp" placeholder="Nomor HP"
                           value="{{ $user->asesor->nomor_hp ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                    <div>
                    <label for="npwp" class="block text-sm font-medium text-gray-700 mb-1">
                        NPWP
                    </label>
                    <input type="text" id="npwp" placeholder="NPWP"
                           value="{{ $user->asesor->NPWP ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col space-y-8 w-full">

            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Informasi Pribadi</h2>
                <div class="space-y-3">
                    <div>
                    <label for="ttl" class="block text-sm font-medium text-gray-700 mb-1">
                        Tempat Tanggal Lahir
                    </label>
                    <input type="text" id="ttl" placeholder="Tempat Tanggal Lahir"
                           value="{{ $user->asesor ? $user->asesor->tempat_lahir . ', ' . $user->asesor->tanggal_lahir : '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                    <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Kelamin
                    </label>
                    <input type="text" id="jenis_kelamin" placeholder="Jenis Kelamin"
                           value="{{ $user->asesor->jenis_kelamin ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                    <div>
                    <label for="Kebangsaan" class="block text-sm font-medium text-gray-700 mb-1">
                        Kebangsaan
                    </label>
                    <input type="text" id="kebangsaan" placeholder="Kebangsaan"
                           value="{{ $user->asesor->kebangsaan ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Informasi Bank</h2>
                <div class="space-y-3">
                    <div>
                    <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Bank
                    </label>
                    <input type="text" id="nama_bank" placeholder="Nama Bank"
                           value="{{ $user->asesor->nama_bank ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                    <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Rekening
                    </label>
                    <input type="text" id="nomor_rekening" placeholder="Nomor Rekening"
                           value="{{ $user->asesor->norek ?? '' }}"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Tombol -->
    <div class="absolute bottom-6 right-6 flex space-x-4">
        <button id="editButton"
                class="flex items-center bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 19H5v-4L15 7z" />
            </svg>
            Edit
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m-6 6h.01" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</main>

<script>
   const editBtn = document.getElementById('editButton');
    const inputs = document.querySelectorAll('.profile-input');
    let editing = false;

    // DAFTAR ID YANG TIDAK AKAN PERNAH BISA DIEDIT (Tidak berubah)
    const nonEditableIds = [
        'no_induk',
        'nama',
        'nik',
        'ttl',
        'jenis_kelamin',
        'kebangsaan'
    ];

    editBtn.addEventListener('click', () => {
        editing = !editing;

        // Daftar input yang BISA DIEDIT
        const editableInputs = Array.from(inputs).filter(input => !nonEditableIds.includes(input.id));

        if (editing) {
            // === MODE EDIT ===
            // 1. Ubah field menjadi editable
            editableInputs.forEach(input => {
                input.readOnly = false;
                input.classList.remove('bg-gray-100', 'border-gray-300');
                input.classList.add('bg-white', 'border-blue-500');
            });

            // 2. Ubah tombol menjadi "Simpan"
            editBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            `;
            editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            editBtn.classList.add('bg-green-600', 'hover:bg-green-700');

        } else {
            // === MODE SIMPAN (SAAT KLIK "SIMPAN") ===
            // 1. Kumpulkan data dari field yang bisa diedit
            const dataToSave = {};
            editableInputs.forEach(input => {
                dataToSave[input.id] = input.value;
            });

            // 2. [BARU] Kirim data ke server menggunakan fetch
            // Pastikan Anda memiliki meta tag CSRF (lihat di bawah)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('profil.asesor.update') }}", { // Kita akan buat route 'profil.update'
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataToSave)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 3. [PINDAH] Jika SUKSES, baru ubah UI kembali
                    alert('Data berhasil disimpan!'); // Ganti dengan notifikasi yang lebih baik jika mau

                    // Kembalikan field ke mode readonly
                    editableInputs.forEach(input => {
                        input.readOnly = true;
                        input.classList.remove('bg-white', 'border-blue-500');
                        input.classList.add('bg-gray-100', 'border-gray-300');
                    });

                    // Kembalikan tombol ke "Edit"
                    editBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 19H5v-4L15 7z" />
                        </svg>
                        Edit
                    `;
                    editBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');

                } else {
                    // Jika GAGAL dari server (misal, validasi error)
                    alert('Gagal menyimpan data: ' + data.message);
                    editing = true; // Tetap di mode editing
                }
            })
            .catch(error => {
                // Jika GAGAL (error jaringan, dll)
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                editing = true; // Tetap di mode editing
            });
        }
    });
</script>

@endsection
