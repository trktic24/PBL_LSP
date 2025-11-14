@extends('layouts.soal')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Jawab Soal</h2>

    <form action="{{ route('jawab.store') }}" method="POST">
        @csrf

        @foreach ($soals as $soal)
            <div class="card mb-4">
                <div class="card-body">
                    <h5>{{ $loop->iteration }}. {{ $soal->soal_IA06 }}</h5>

                    @foreach ($soal->kuncis as $kunci)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="jawaban[{{ $soal->id_soal_IA06 }}]"
                                   id="kunci_{{ $kunci->id_kunci_IA06 }}"
                                   value="{{ $kunci->id_kunci_IA06 }}">
                            <label class="form-check-label" for="kunci_{{ $kunci->id_kunci_IA06 }}">
                                {{ $kunci->kunci_IA06 }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
    </form>
</div>
@endsection
