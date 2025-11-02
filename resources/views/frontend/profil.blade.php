@extends('layouts.app-profil')
@section('content')

<!-- KONTEN PROFIL -->
<main class="max-w-6xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md relative pb-20">
    <div class="flex gap-8">

        <!-- KOLOM KIRI: FOTO PROFIL + INFORMASI PRIBADI -->
        <div class="flex flex-col items-center space-y-24 w-[40%] min-w-[400px] max-w-[550px]">
            <!-- FOTO PROFIL -->
            <div class="w-32 h-32 rounded-full border-4 border-blue-500 flex items-center justify-center overflow-hidden">
                <img src="{{ Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg') }}" 
                     alt="Foto Profil" 
                     class="object-cover w-full h-full">
            </div>

            <!-- INFORMASI PRIBADI -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-3 text-left">Informasi Pribadi</h2>
                <div class="space-y-3">
                    <input type="text" id="ttl" placeholder="Tempat Tanggal Lahir"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                    <input type="text" id="jenis_kelamin" placeholder="Jenis Kelamin"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                    <input type="text" id="kebangsaan" placeholder="Kebangsaan"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                </div>

                <h2 class="text-lg font-semibold mb-3 mt-6 text-left">Alamat & Kontak</h2>
                <div class="space-y-3">
                    <textarea id="alamat_rumah" placeholder="Alamat Rumah"
                        class="profile-input w-full border h-[100px] border-blue-500 rounded-md px-3 py-2"
                        readonly></textarea>
                    <input type="text" id="nomor_hp" placeholder="Nomor HP"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                    <input type="text" id="npwp" placeholder="NPWP"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                </div>

                <h2 class="text-lg font-semibold mb-3 mt-6 text-left">Informasi Bank</h2>
                <div class="space-y-3">
                    <input type="text" id="nama_bank" placeholder="Nama Bank"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                    <input type="text" id="nomor_rekening" placeholder="Nomor Rekening"
                        class="profile-input w-full border border-blue-500 rounded-md px-3 py-2"
                        readonly>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: DATA PRIBADI -->
        <div class="flex-1">
            <h2 class="text-lg font-semibold mb-3">Data Pribadi</h2>
            <div class="space-y-3">
                <input type="text" id="nama" placeholder="Nama Lengkap"
                    class="profile-input w-full border border-blue-500 rounded-md px-3 py-2" readonly>
                <input type="text" id="nik" placeholder="NIK"
                    class="profile-input w-full border border-blue-500 rounded-md px-3 py-2" readonly>
                <input type="text" id="no_induk" placeholder="No Registrasi Asesor"
                    class="profile-input w-full border border-blue-500 rounded-md px-3 py-2" readonly>
                <input type="text" id="kompetensi" placeholder="Kompetensi"
                    class="profile-input w-full border border-blue-500 rounded-md px-3 py-2" readonly>
            </div>
        </div>
    </div>

    <!-- TOMBOL DI POJOK KANAN BAWAH -->
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


    <!-- SCRIPT: Mode Edit -->
    <script>
        const editBtn = document.getElementById('editButton');
        const inputs = document.querySelectorAll('.profile-input');
        let editing = false;

        editBtn.addEventListener('click', () => {
            editing = !editing;

            inputs.forEach(input => input.readOnly = !editing);

            if (editing) {
                editBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                `;
                editBtn.classList.remove('bg-blue-600');
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
