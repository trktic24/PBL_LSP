@extends('layouts.soal')

@section('content')
<div class="container mt-4">
    <h2>Hasil Jawaban</h2>

    <ul class="list-group mt-3">
        @foreach ($hasil as $data)
            <li class="list-group-item">
                <strong>{{ $data['soal'] }}</strong><br>
                Jawaban kamu: {{ $data['jawaban'] }}
            </li>
        @endforeach
    </ul>

    <a href="{{ route('jawab.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
