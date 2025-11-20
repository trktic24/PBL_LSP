{{-- File: resources/views/layouts/wizard.blade.php --}}
@extends('layouts.app-sidebar')

@section('content')
    <div class="w-full flex">

        {{-- Wrapper konten wizard --}}
        <div class="w-full max-w-[90%] md:max-w-3xl lg:max-w-5xl px-4 md:px-6 py-8 md:py-10 mx-auto">

            {{-- Slot untuk isi halaman (judul/tabel/dll) --}}
            @yield('wizard-content')

        </div>

    </div>
@endsection
