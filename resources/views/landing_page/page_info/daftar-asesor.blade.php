@extends('layouts.app-profil')
@section('content')
    <!-- Import Google Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body, table, th, td, input, button {
            font-family: 'Poppins', sans-serif !important;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        th, td {
            vertical-align: middle;
        }
        th {
            background-color: #FEF9C3; /* yellow-50 */
        }
        .table-container {
            border-radius: 1.5rem;
            overflow: hidden;
        }
    </style>

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Asesor</h1>
            <p class="text-gray-600">Berikut daftar asesor<br>beserta bidang pekerjannya</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Box -->
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ url('/daftar-asesor') }}" class="relative">
                <input
                    type="text"
                    name="search"
                    placeholder="Search"
                    value="{{ request('search') }}"
                    class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                >
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <div class="table-container bg-yellow-50 shadow-md border border-gray-200">
            <table>
                <!-- Table Header -->
                <thead class="border-b-2 border-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[25%]">Nama Asesor</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[20%]">No. Registrasi</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[25%]">Bidang Pekerjaan</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[15%]">Provinsi</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[15%]">Jumlah Asesmen</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody id="tableBody">
                    @forelse($asesors as $asesor)
                        <tr class="border-b border-gray-200 bg-yellow-50 hover:bg-yellow-100 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 text-left truncate">{{ $asesor->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $asesor->nomor_regis }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center truncate">{{ $asesor->pekerjaan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $asesor->provinsi }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ rand(70,130) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-gray-500 text-center">Tidak ada data asesor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
