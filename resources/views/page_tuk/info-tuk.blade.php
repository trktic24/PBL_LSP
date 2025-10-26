@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')

{{-- Kosongkan header, navbar, footer --}}
@section('header', '')
@section('navbar', '')
@section('footer', '')

@section('content')
<div class="container mx-auto px-4 py-12">
    
    <h1 class="text-3xl font-semibold text-center mb-10 text-gray-800">Tempat Uji Kompetensi</h1>

    <div class="bg-white rounded-lg shadow-xl overflow-hidden border-t-8 border-blue-600">
        <div class="overflow-x-auto">
            <table id="tuk-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50 border-b-2 border-blue-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-800 uppercase tracking-wider">Tempat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-800 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-800 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-blue-800 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 text-base text-gray-800">Politeknik Negeri Semarang <br> Gedung Kuliah Terpadu</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">(024) 7473417 ext.256</td>
                        <td class="px-6 py-4 text-center">
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-gkt') }}" />
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 text-base text-gray-800">Politeknik Negeri Semarang <br> MST LT3</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">25 Oktober 2025</td>
                        <td class="px-6 py-4 text-center">
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-mst-lt3') }}" />
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 text-base text-gray-800">Politeknik Negeri Semarang <br> Gedung Sekolah Satu</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">(024) 7473417 ext.256</td>
                        <td class="px-6 py-4 text-center">
                            <x-detail-tuk-button url="{{ url('/info-tuk/detail/polines-gedung-satu') }}" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
