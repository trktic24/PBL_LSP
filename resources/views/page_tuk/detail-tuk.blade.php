@extends('layouts.detail-tuk-master')

@section('title', 'Detail Tempat Uji Kompetensi')

@section('content')

    <h1 class="page-title">Tempat Uji Kompetensi</h1>

    <div class="kompetensi-card">
        <div class="card-info">
            <h2 class="card-title">Politeknik Negeri Semarang</h2>
            
            {{-- Detail Alamat --}}
            <div class="contact-detail">
                {{-- Gunakan asset() untuk gambar ikon di public/img/ --}}
                <img src="{{ asset('img/google-maps.png') }}" alt="Ikon Google Maps" class="google-maps-icon">
                <p>Jalan prof Soedarto</p>
            </div>
            
            {{-- Detail Telepon --}}
            <div class="contact-detail">
                {{-- Gunakan asset() untuk gambar ikon di public/img/ --}}
                <img src="{{ asset('img/telephone-call.png') }}" alt="Ikon Telepon" class="telephone-call-icon">
                <p>082185859493</p>
            </div>
            
            {{-- Tombol Buka di Google Maps --}}
            <a href="#" class="btn btn-google-maps">
                {{-- Gunakan asset() untuk gambar ikon di public/img/ --}}
                <img src="{{ asset('img/google-maps.png') }}" alt="Ikon Google Maps" class="google-maps-icon">
                Buka di Google Maps
            </a>
        </div>
        
        {{-- Placeholder Gambar Lokasi --}}
        <div class="card-image-placeholder">
            <span class="icon-placeholder">Gambar Anda</span>
        </div>
    </div>

@endsection