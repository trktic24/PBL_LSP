@extends('layouts.app-sidebar-skema')

{{-- Menetapkan Judul Halaman --}}
@section('title', 'Daftar Hadir Peserta')
@section('content')

    <div class="p-0">
            <div class="flex flex-col xl:w-auto flex-1 justify-between mx-auto mb-8">
                <div class="w-full flex flex-col items-center">
                    
                    {{-- Logo di tengah dan lebih besar --}}
                    <img src="{{ asset('images/Logo_LSP_No_BG.png') }}"
                        alt="Logo LSP Polines"
                        class="h-20 md:h-24 w-auto mb-2"> 

                    {{-- Judul di bawah logo dan rata tengah --}}
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6 mt-2">
                        Berita Acara Asesmen
                    </h2>

                </div>


                <div class="w-full xl:w-auto flex-1 flex mx-auto">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm w-full mx-auto">
                        <div class="flex flex-col gap-3">
                            <div class="w-full flex flex-col items-center text-sm text-gray-600">
                                <p>Pada hari ini, Hari/Tanggal: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }} , Waktu: Pukul {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '10:20:00')->format('H:i') }} s/d Selesai, bertempat di TUK {{ $jadwal->masterTuk->nama_lokasi }}, 
                                    telah dilaksanakan proses asesmen terhadap asesi pada sektor / sub sektor / bidang profesi {{ $jadwal->skema->nama_skema }} yang diikuti oleh <strong>{{ $pendaftar->total() }} orang peserta</strong>. Dari hasil asesmen, peserta yang dinyatakan <strong>kompeten</strong>
                                    adalah <strong>{{ $jumlahKompeten > 0 ? $jumlahKompeten . ' orang peserta' : 'tidak ada' }}</strong> dan yang <strong>belum kompeten</strong> adalah <strong>{{ $jumlahBelumKompeten > 0 ? $jumlahBelumKompeten . ' orang peserta' : 'tidak ada' }}</strong> dengan perincian sebagai berikut:
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">

                <table class="min-w-full text-xs text-left border border-gray-200">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            <th class="px-4 py-3 font-semibold w-16 text-center">
                                <span>ID</span>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                <span>Nama Peserta</span>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                <span>Hasil Asesmen</span>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                <span>Rekomendasi/ Tindak Lanjut</span>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                <span>Keterangan</span>
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pendaftar as $index => $data)
                        
                        <tr class="hover:bg-blue-50 transition divide-x divide-gray-200 cursor-pointer group">
                            
                            <td class="px-4 py-4 text-center font-medium text-gray-500">
                                {{ $data->id_data_sertifikasi_asesi }}
                            </td>
                            
                            <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-700 transition-colors">
                                {{ $data->asesi->nama_lengkap }}
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->rekomendasi === 'K' ? 'Kompeten' : 'Belum Kompeten' }}
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->rekomendasi === 'K' ? 'Terbitkan Sertifikat' : 'Mengulang Asesmen' }}
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->keterangan }}
                                @endif
                            </td>     
                            
                            @empty
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada peserta yang mendaftar di jadwal ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="w-full xl:w-auto flex-1 flex mx-auto mt-10">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm w-full">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-gray-600">
                            <p>Demikian berita acara ini dibuat dengan sebenarnya, untuk digunakan sebagaimana mestinya.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">
                    {{-- BAGIAN ASESOR --}}
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-700 mb-3">Semarang, {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</h3>
                            <h4 class="font-medium text-gray-800">Asesor</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">{{ $asesor->nama_lengkap }}</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                <img src="{{ route('secure.file', ['path' => $asesor->tanda_tangan]) }}" 
                                     alt="Tanda Tangan Asesor" 
                                     class="h-20 w-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                            </div>
                        </div>

                    {{-- BAGIAN PJ KEGIATAN --}}
                        <div class="space-y-3 md:mt-10">
                            <h4 class="font-medium text-gray-800">Penanggungjawab Kegiatan</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">Ajeng Febria H.</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                <img src="{{ route('secure.file', ['path' => $asesor->tanda_tangan]) }}" 
                                    class="w-20 h-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                            </div>
                        </div>
                    
                </div>
            </div>
        </main>
    </div>

@endsection