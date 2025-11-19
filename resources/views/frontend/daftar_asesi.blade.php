@extends('layouts.app-sidebar-skema')
@section('content')

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <div class="flex-1 p-8">
        
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 ml-10">
            <div class="grid grid-cols-[150px,1fr] gap-x-6 gap-y-2 text-sm">
                <div class="font-semibold text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</div>
                <div>:</div>
                <div class="font-semibold text-gray-700">Judul</div>
                <div>:</div>
                <div class="font-semibold text-gray-700">Nomor</div>
                <div>:</div>
                <div class="font-semibold text-gray-700">Tanggal</div>
                <div>:</div>
                <div class="font-semibold text-gray-700">Waktu</div>
                <div>:</div>
                <div class="font-semibold text-gray-700">TUK</div>
                <div>:</div>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6 ml-10">Daftar Asesi</h2>

        <div class="ml-10"> 
            
            <div class="flex flex-col gap-4"> 

                <div class="bg-white rounded-lg shadow-xl overflow-x-auto w-full"> 
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">No</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Nama Asesi</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Pra Asesmen</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Asesmen</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Semua</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Asesmen Mandiri</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Penyesuaian</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            {{-- Semua baris <tr> Anda --}}
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">1</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Tatang Sitartang</td>
                                <td class="text-yellow-600 font-medium">
                                    <a href="{{ route('tracker') }}" class="p-4 text-sm text-yellow-600 font-medium">
                                        Dalam Proses
                                    </a>
                                </td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>
                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">2</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Jojon Sudarman</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>
                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">3</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Abdul Sidarta M.</td>
                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>
                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>
                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>
                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block" checked>
                                </td>
                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">4</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Mustika Pujastuti</td>
                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>
                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>
                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>
                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>
                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-4 justify-between">
                    <button class="bg-blue-800 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-blue-900 flex-grow">
                        Daftar Hadir
                    </button>
                    <button class="bg-blue-800 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-blue-900 flex-grow">
                        Laporan Asesmen
                    </button>
                    <button class="bg-blue-800 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-blue-900 flex-grow">
                        Tinjauan Asesmen
                    </button>
                    <button class="bg-blue-800 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-blue-900 flex-grow">
                        Berita Acara
                    </button>
                </div>
            </div> 
            </div> 
        </div>
</main>

@endsection