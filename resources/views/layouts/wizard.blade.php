{{-- File: resources/views/layouts/wizard.blade.php --}}
@extends('layouts.app-sidebar')

@section('content')
    <div class="w-full">

        {{-- Wrapper konten wizard --}}
        <div class="w-full px-4 md:px-8 py-8 mx-auto">

            {{-- Slot untuk isi halaman (judul/tabel/dll) --}}
            @yield('wizard-content')

        </div>

    </div>
@endsection
