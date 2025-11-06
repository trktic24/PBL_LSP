@extends('layouts.app-profil')

@section('content')

<div class="p-8">
        <h1 class="text-2xl font-bold">Halo, Selamat Datang di Dashboard Asesi</h1>
        <p>Email kamu: {{ auth()->user()->email }}</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">
                Logout
            </button>
        </form>
    </div>
@endsection
