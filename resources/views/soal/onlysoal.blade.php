@extends('layouts.soal')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Daftar Soal</h2>

    @foreach ($soals as $soal)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $loop->iteration }}. {{ $soal->soal_IA06 }}</h5>

                <ul class="list-group list-group-flush mt-2">
                    @foreach ($soal->kuncis as $kunci)
                        <li class="list-group-item">
                            {{ $kunci->kunci_IA06 }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach

    @if ($soals->isEmpty())
        <div class="alert alert-info text-center">
            Belum ada soal yang tersedia.
        </div>
    @endif
</div>
@endsection
