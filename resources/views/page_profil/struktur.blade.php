@extends('layouts.app-profil')

@section('title', 'Struktur Organisasi')

@section('content')
    <section id="struktur" class="section">
        <div class="container text-center">
            <h2 class="section-title">Struktur Organisasi</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-4">
                    <div class="org-box org-box-top">JABATAN</div>
                </div>
            </div>
            
            <div class="row justify-content-center mt-3">
                <div class="col-lg-3 col-md-4">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
            </div>
            
            <div class="row justify-content-center mt-3">
                <div class="col-lg-2 col-md-3">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
                 <div class="col-lg-2 col-md-3">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
                 <div class="col-lg-2 col-md-3">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
                 <div class="col-lg-2 col-md-3">
                    <div class="org-box org-box-mid">JABATAN</div>
                </div>
            </div>
            <p class="text-muted mt-4 small">(Catatan: Garis-garis penghubung struktur biasanya ditambahkan menggunakan CSS kustom yang lebih kompleks atau gambar.)</p>
        </div>
    </section>
@endsection