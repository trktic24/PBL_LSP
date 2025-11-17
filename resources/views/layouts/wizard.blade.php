{{-- File: resources/views/layouts/wizard.blade.php --}}
@extends('layouts.app-sidebar')

@section('content')
    {{-- Kontainer utama untuk wizard di tengah --}}
    <div class="max-w-5xl mx-auto">
            @yield('wizard-content')
    </div>
@endsection
