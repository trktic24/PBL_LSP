@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')

@section('content')
<div class="container">
    <h1 style="text-align: center; margin-top: 3rem; color: #333;">Tempat Uji Kompetensi</h1>

    <div class="card" style="border-top: 4px solid #0d47a1; padding: 1rem 0;">
        <div class="table-responsive">
            <table class="tuk-table">
                <thead>
                    <tr>
                        <th>Tempat</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris 1 --}}
                    <tr>
                        <td>Politeknik Negeri Semarang <br> Gedung Kuliah Terpadu</td>
                        <td>Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td>(024) 7473417 ext.256</td>
                        <td>
                            <x-button-detail url="#" />
                        </td>
                    </tr>

                    {{-- Baris 2 --}}
                    <tr>
                        <td>Politeknik Negeri Semarang <br> MST LT3</td>
                        <td>Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td>25 Oktober 2025</td>
                        <td>
                            <x-button-detail url="#" />
                        </td>
                    </tr>

                    {{-- Baris 3 --}}
                    <tr>
                        <td>Politeknik Negeri Semarang <br> Gedung Sekolah Satu</td>
                        <td>Jl. Prof. Soedarto, SH. <br> Tembalang, Semarang, Jawa Tengah.</td>
                        <td>(024) 7473417 ext.256</td>
                        <td>
                            <x-button-detail url="#" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection