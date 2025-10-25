@extends('layouts.app-profil')

@section('title', 'Mitra Kami')

@section('content')
    <section id="mitra" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Mitra Kami</h2>
            <p class="text-center text-muted fs-5" style="margin-top: -40px; margin-bottom: 40px;">Lorem ipsum dolor sit amet</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <img src="https://dicoding-web-img.sgp1.cdn.digitaloceanspaces.com/original/commons/dicoding-logo-full.png" alt="Dicoding Logo" class="dicoding-logo">
                            <h5 class="card-title fw-bold text-center">Dicoding</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="logo-placeholder">Logo</div>
                            <h5 class="card-title fw-bold text-center">Lorem Ipsum dolor</h5>
                            <p class="card-text text-center text-muted">sit amet</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="logo-placeholder">Logo</div>
                            <h5 class="card-title fw-bold text-center">Lorem Ipsum dolor</h5>
                            <p class="card-text text-center text-muted">sit amet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection