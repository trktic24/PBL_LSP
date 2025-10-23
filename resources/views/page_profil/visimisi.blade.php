@extends('layouts.app-profil')

@section('title', 'Visi & Misi')

@section('content')
    <section id="visi-misi" class="section bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <img src="https://lsp.polines.ac.id/wp-content/uploads/2022/02/cropped-logo-lsp-polines-web-3.png" alt="LSP POLINES" class="mb-3" style="height: 60px;">
                <p class="text-muted fs-5">Lorem ipsum dolor sit amet</p>
            </div>
            
            <div class="row g-4 justify-content-center ">
                <div class="col-lg-5 col-md-6">
                    <div class="card">
                        <div class="card-body text-center  ">
                            <h3 class="card-title fw-bold mb-3">Visi</h3>
                            <p class="card-text text-muted">Lorem ipsum dolor sit amet, you're the best person i've ever met</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="card-title fw-bold mb-3">Misi</h3>
                            <p class="card-text text-muted">Lorem ipsum dolor sit amet, you're the best person i've ever met</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4 justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body p-5">
                            <h3 class="card-title fw-bold mb-4 text-center">Tujuan</h3>
                            <ol>
                                <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                                <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                                <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                                <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                                <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection