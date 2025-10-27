<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tempat Uji Kompetensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* PERBAIKAN KRITIS: Menghilangkan margin dan padding default */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        body {
            /* Warna background utama yang netral (TETAP Sesuai Input) */
            background-color: #edeef3; 
            min-height: 100vh;
        }
        
        /* Layout Utama: Mengisi Penuh Layar Vertikal & Horizontal */
        .main-layout {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }
        
        /* Gradient Sidebar Kiri - (TETAP Sesuai Input) */
        .sidebar-left {
            background: linear-gradient(180deg, #FDFDE0 0%, #F0F8FF 100%);
            min-height: 100vh;
        }
        
        /* LINGKARAN PROFIL */
        .profile-image-container {
            width: 150px; 
            height: 150px;
            display: flex;
            flex-direction: column; 
            align-items: center;
            justify-content: center;
            border: 5px solid #fff; 
            background-color: #FDFDE0; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border-radius: 50%; 
            line-height: 1.2; 
            margin: auto;
            margin-bottom: 2rem;
        }

        /* Memastikan konten kanan juga mengisi tinggi penuh */
        .content-right {
             min-height: 100vh; 
        }
    </style>
</head>
<body class="font-sans">

    <div class="main-layout flex">
        
        <div class="sidebar-left w-80 p-8 text-center box-border flex-shrink-0">
            <a href="tracker.html" class="back-link flex items-center text-left mb-10 text-sm font-semibold text-gray-700 hover:text-black no-underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>
            
            <h2 class="text-3xl font-bold mt-0 mb-8 text-gray-900 text-left">Skema Sertifikat</h2>
            
            <div class="profile-image-container mx-auto mb-8">
                <span class="text-lg font-semibold text-gray-800 leading-tight text-center">Junior Web<br>Developer</span>
            </div>

            <div class="certification-details">
                <h3 class="text-xl font-semibold text-gray-900">Junior Web Developer</h3>
                <p class="text-sm text-gray-500">SKM12XXXXXXX</p>
                <p class="text-xs text-gray-600 italic mb-8">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>
                
                <p class="assessor-name font-bold text-gray-900">Nama Asesor</p>
                <p class="assessor-info text-sm text-gray-700 mb-8">
                    Jajang Sokbreker, S.T., M.T.<br>
                    No. Reg. MET00xxxx.xxxxxx
                </p>
                
                <p class="tuk-info font-bold mt-4 text-gray-900 text-center">
                    TUK
                    <small class="block font-normal text-xs text-gray-700">Jl. Prof. Sudarto sesko ssesas</small>
                </p>
                </div>
        </div>

        <div class="content-right flex-grow p-10 bg-white box-border">
            <h1 class="text-3xl font-bold text-gray-800 pb-4 mb-3">Verifikasi Tempat Uji Kompetensi</h1>
            <h4 class="text-base text-gray-500 mb-8 font-normal">Bukti kelengkapan persyaratan dasar pemohon</h4>
            
            <div class="space-y-5">
                
                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Video 360° Ruangan</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Video 360° Ruangan-Uji Kompetensi</span>
                            <span>ruang.mp4</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Foto Peralatan / Perlengkapan Sesuai Skema</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Foto Peralatan / Perlengkapan Sesuai Skema</span>
                            <span>alat.png</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>
                
                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Foto Tampak Ruangan</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Foto Tampak Ruangan</span>
                            <span>FTBtuang.png</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Paket Data Internet</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Foto Paket Data Internet</span>
                            <span>pktData.png</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Kecepatan Internet</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Foto Kecepatan Internet</span>
                            <span>inst.png</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="verification-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg text-gray-800">Foto Ruang Sertifikasi</strong>
                        <small class="text-sm font-bold text-red-600">0 berkas ^</small>
                    </div>
                    <div class="upload-area border border-dashed border-gray-300 p-4 rounded bg-white">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span class="font-bold">Foto Ruang Sertifikasi</span>
                            <span>#RSer.png</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <input type="text" placeholder="Tambahkan Keterangan" class="p-2 border border-gray-300 rounded-md flex-grow text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button class="btn btn-upload px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">Upload</button>
                            <button class="btn btn-cancel px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition border 
                            border-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex justify-end items-center mt-16">
                <button class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 transition-colors mr-4">
                    Batal
                </button>
                <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 shadow-md transition-colors">
                    Selanjutnya
                </button>
            </div>

        </div>
    </div>

</body>
</html>