{{-- File: resources/views/layouts/wizard.blade.php --}}
@extends('layouts.app-sidebar')

@section('content')
    {{-- Kontainer utama untuk wizard di tengah --}}
    <div class="max-w-5xl mx-auto">



        {{-- 2. Konten Form (yang di-yield dari tiap step) --}}
        <div class="bg-white p-8 rounded-lg shadow-lg">
            @yield('wizard-content') {{-- Tiap halaman form akan ngisi ini --}}
        </div>

    </div>
@endsection
