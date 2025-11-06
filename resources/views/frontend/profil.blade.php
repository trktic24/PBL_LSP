@extends('layouts.app-profil')
@section('content')

<main class="max-w-6xl mx-auto my-auto bg-white p-8 rounded-lg shadow-md relative pb-20">

    <div class="flex flex-col items-center space-y-2 mb-10">
        <div class="w-32 h-32 rounded-full border-4 border-blue-500 flex items-center justify-center overflow-hidden relative group cursor-pointer">
            <img src="{{ Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg') }}" 
                 alt="Foto Profil" 
                 class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">

        <div class="flex flex-col space-y-8">
            
            <div>
                <h2 class="text-lg font-semibold mb-3">Data Pribadi</h2>
                <div class="space-y-3">
                    <input type="text" id="no_induk" placeholder="No Registrasi Asesor"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    <input type="text" id="nama" placeholder="Nama Lengkap"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    <input type="text" id="nik" placeholder="NIK"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Alamat & Kontak</h2>
                <div class="space-y-3">
                    <textarea id="alamat_rumah" placeholder="Alamat Rumah"
                              class="profile-input w-full border h-[100px] border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                              readonly></textarea>
                    <input type="text" id="nomor_hp" placeholder="Nomor HP"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    <input type="text" id="npwp" placeholder="NPWP"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                </div>
            </div>
            
            </div> <div class="flex flex-col space-y-8">
            
            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Informasi Pribadi</h2>
                <div class="space-y-3">
                    <input type="text" id="ttl" placeholder="Tempat Tanggal Lahir"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    <input type="text" id="jenis_kelamin" placeholder="Jenis Kelamin"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    <input type="text" id="kebangsaan" placeholder="Kebangsaan"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3 text-left">Informasi Bank</h2>
                <div class="space-y-3">
                    <input type="text" id="nama_bank" placeholder="Nama Bank"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                    <input type="text" id="nomor_rekening" placeholder="Nomor Rekening"
                           class="profile-input w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                           readonly>
                </div>
            </div>

        </div> </div> <div class="absolute bottom-6 right-6 flex space-x-4">
        <button id="editButton"
                class="flex items-center bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 19H5v-4L15 7z" />
            </svg>
            Edit
        </button>
        <button id="logoutButton"
                class="flex items-center bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m-6 6h.01" />
            </svg>
            Logout
        </button>
    </div>
</main>


<script>
    const editBtn = document.getElementById('editButton');
    const inputs = document.querySelectorAll('.profile-input');
    let editing = false;

    editBtn.addEventListener('click', () => {
        editing = !editing;

        inputs.forEach(input => {
            // 1. Toggle readonly
            input.readOnly = !editing;

            // 2. [BARU] Toggle style visual
            if (editing) {
                // Hapus style 'readonly', tambahkan style 'editable'
                input.classList.remove('bg-gray-100', 'border-gray-300');
                input.classList.add('bg-white', 'border-blue-500'); // Style biru dari kode asli Anda
            } else {
                // Hapus style 'editable', tambahkan style 'readonly'
                input.classList.remove('bg-white', 'border-blue-500');
                input.classList.add('bg-gray-100', 'border-gray-300'); // Style abu-abu dari target
            }
        });

        // 3. Logika tombol (Tidak diubah)
        if (editing) {
            editBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            `;
            editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            editBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        } else {
            // Simulasikan penyimpanan data (bisa diganti dengan fetch/axios ke backend)
            alert('Data berhasil disimpan!');
            
            editBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 19H5v-4L15 7z" />
                </svg>
                Edit
            `;
            editBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    });
</script>

@endsection