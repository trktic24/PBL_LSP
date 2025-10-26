@extends('layouts.detail-tuk-master')

@section('title', 'Alur Proses Sertifikasi')

@section('content')

    <div class="alur-container">
        <h1 class="page-title">Alur Proses Sertifikasi</h1>

        {{-- START: Timeline Container (HTML Murni) --}}
        <div class="timeline-wrapper">

            {{-- Garis Vertikal (Placeholder) --}}
            <div class="timeline-line"></div>
            
            <div class="timeline-items">

                {{-- LANGKAH 1: Pendaftaran & Verifikasi (Placeholder Highlight) --}}
                <div class="timeline-item">
                    <div class="timeline-circle is-active"></div>
                    <div class="timeline-card highlight-step-1">
                        <h3>Pendaftaran & Verifikasi Dokumen</h3>
                        <p>lorem ipsum dolor sit amet</p>
                    </div>
                </div>

                {{-- LANGKAH 2: Pembayaran (Placeholder Normal) --}}
                <div class="timeline-item">
                    <div class="timeline-circle"></div>
                    <div class="timeline-card normal-step">
                        <h3>Pembayaran</h3>
                        <p>lorem ipsum dolor sit amet</p>
                    </div>
                </div>

                {{-- LANGKAH 3: Pelaksanaan Asesmen (Placeholder Normal) --}}
                <div class="timeline-item">
                    <div class="timeline-circle"></div>
                    <div class="timeline-card normal-step">
                        <h3>Pelaksanaan Asesmen Kompetensi</h3>
                        <p>lorem ipsum dolor sit amet</p>
                    </div>
                </div>

                {{-- LANGKAH 4: Penerbitan Sertifikat (Placeholder Highlight) --}}
                <div class="timeline-item">
                    <div class="timeline-circle is-active"></div>
                    <div class="timeline-card highlight-step-4">
                        <h3>Penerbitan Sertifikat</h3>
                        <p>lorem ipsum dolor sit amet</p>
                    </div>
                </div>

            </div>
        </div>
        {{-- END: Timeline Container --}}

    </div>

@endsection