<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <aside class="w-80 flex-shrink-0 p-8" style="background: linear-gradient(180deg, #FDFDE0 0%, #F0F8FF 100%);">
            
            <a href="/" class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>

            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Skema Sertifikat</h2>
                
                <img src="https://plus.unsplash.com/premium_photo-1661763171882-685b838f50b7?w=128&h=128&fit=crop&q=80" 
                     alt="Junior Web Developer" 
                     class="w-28 h-28 rounded-full mx-auto mb-4 border-4 border-white shadow-lg object-cover">
                
                <h1 class="text-xl font-semibold text-gray-900">Junior Web Developer</h1>
                <p class="text-sm text-gray-500 mb-4">SKM12XXXXXX</p>
                
                <p class="text-xs text-gray-600 italic px-2 mb-6">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>

                <div class="text-left p-4 bg-white/60 rounded-lg shadow-inner backdrop-blur-sm">
                    <h3 class="text-sm font-semibold text-gray-800">Nama Asesor</h3>
                    <p class="text-sm text-gray-700">Jojang Sokbreker, S.T., M.T.</p>
                    <p class="text-xs text-gray-500 mt-1">No. Reg. MET.190XXXXXXXX</p>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-10 overflow-y-auto">
            <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-xl">
                
                <ol class="relative">

                    @php
                    function renderCheckmark() {
                        return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>';
                    }
                    @endphp

                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <a href="\data_sertifikasi" class="text-lg font-semibold text-gray-900">Formulir Pendaftaran Sertifikasi</a>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Diterima</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Document
                            </button>
                        </div>
                    </li>

                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <a href="/praasesmen1" class="text-lg font-semibold text-gray-900">Pra-Asesmen</a>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Diterima</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Document
                            </button>
                        </div>
                    </li>

                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 11.25h6.75M9 15.75h6.75M9 20.25h6.75" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Verifikasi TUK</h3>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Diterima</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Document
                            </button>
                        </div>
                    </li>

                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Persetujuan Asesmen dan Kerahasiaan</h3>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Diterima</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Document
                            </button>
                        </div>
                    </li>

                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 006.75 5.625v1.5c0 .621.504 1.125 1.125 1.125h.375m-3.75 0h16.5v1.5c0 .621-.504 1.125-1.125 1.125h-14.25c-.621 0-1.125-.504-1.125-1.125v-1.5z" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Asesmen</h3>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h4 class="font-medium text-gray-800">Cek Observasi - Demonstrasi/Praktek</h4>
                                <p class="text-sm text-gray-500 mt-1">Jumat, 29 September 2025 15.16</p>