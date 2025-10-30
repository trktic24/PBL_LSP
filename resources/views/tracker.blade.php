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
        
        <x-sidebar></x-sidebar>

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
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V15m6-1.5h.008v.008H18V13.5z" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!} 
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Pembayaran</h3>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Lunas</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Invoice
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
                            
                            <div class="space-y-4">
                                
                                <div class="flex justify-between items-start space-x-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Cek Observasi - Demonstrasi/Praktek</h4>
                                        <p class="text-sm text-gray-500 mt-1">Jumat, 29 September 2025 15.16</p>
                                        <p class="text-xs text-gray-500">Diterima</p>
                                        <p class="text-sm text-gray-500 mt-2">Jumat, 29 September 2025 20.16</p>
                                        <p class="text-xs text-gray-500">Diterima</p>
                                        <p class="text-xs text-gray-500">Rekomendasi Kompeten</p>
                                    </div>
                                    <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 flex-shrink-0">
                                        Unduh Document
                                    </button>
                                </div>

                                <div class="flex justify-between items-start space-x-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Pertanyaan Lisan</h4>
                                        <p class="text-sm text-gray-500 mt-1">Jumat, 29 September 2025 15.16</p>
                                        <p class="text-xs text-gray-500">Diterima</p>
                                        <p class="text-sm text-gray-500 mt-2">Jumat, 29 September 2025 20.16</p>
                                        <p class="text-xs text-gray-500">Diterima</p>
                                        <p class="text-xs text-gray-500">Rekomendasi Kompeten</p>
                                    </div>
                                    <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 flex-shrink-0">
                                        Unduh Document
                                    </button>
                                </div>

                            </div>
                        </div>
                    </li>
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
                            <h3 class="text-lg font-semibold text-gray-900">Keputusan dan umpan balik Asesor</h3>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Umpan balik peserta / banding</h3>
                            <p class="text-sm text-gray-500">Jumat, 29 September 2025</p>
                            <p class="text-xs text-green-600 font-medium">Diterima</p>
                            <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                Unduh Document
                            </button>
                        </div>
                    </li>

                    <li class="relative flex items-start"> <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.462 48.462 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c.317.053.626.111.928.174m-15.356 0c.317.053.626.111.928.174m13.5 0L12 12m0 0L6.25 4.97M12 12v8.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M12 12h8.25m-8.25 0H3.75" />
                                </svg>
                            </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Keputusan Komite</h3>
                                <p class="text-sm text-gray-500">Direkomendasikan Menerima Sertifikat</p>
                                
                                <button class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                    Unduh Sertifikat
                                </button>
                            </div>
                    </li>
                    
                </ol> </div> </main> </div> </body>
</html>