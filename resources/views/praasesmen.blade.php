<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pra - Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    @php
        // Data skema - bisa diganti dengan data dari controller
        $currentPage = request()->get('page', 1); // Default page 1
        
        // Data Asesor (nanti bisa diambil dari database melalui controller)
        $asesor = [
            'nama' => 'Jajang Sokbreker, S.T., M.T.',
            'no_reg' => 'No. Reg. MET00XXXXX.XXXXX'
        ];
        
        $skemaList = [
            1 => [
                'judul' => 'Menggunakan Struktur Data',
                'unit' => 1,
                'pertanyaan' => [
                    [
                        'judul' => 'Mengidentifikasi Konsep Data dan Struktur Data',
                        'sub' => [
                            'Konsep data dan struktur data diidentifikasi sesuai dengan konteks',
                            'Alternatif struktur data dibandingkan kekurangannya untuk konteks permasalahan yang diselesaikan'
                        ]
                    ],
                    [
                        'judul' => 'Menerapkan struktur data dan akses terhadap struktur data tersebut',
                        'sub' => [
                            'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang dipergunakan',
                            'Akses terhadap data dinyatakan dalam algoritma yang efisien sesuai bahasa pemrograman yang akan dipakai'
                        ]
                    ]
                ]
            ],
            2 => [
                'judul' => 'Mengimplementasikan User Interface',
                'unit' => 2,
                'pertanyaan' => [
                    [
                        'judul' => 'Mengidentifikasi Rancangan User Interface',
                        'sub' => [
                            'Rancangan user interface diidentifikasi sesuai kebutuhan',
                            'Komponen user interface dialog diidentifikasi sesuai konteks rancangan proses',
                            'Urutan dari akses komponen user interface dialog dijelaskan',
                            'Simulasi (mock-up) dari aplikasi yang akan dikembangkan dibuat'
                        ]
                    ],
                    [
                        'judul' => 'Melakukan implementasi rancangan user interface',
                        'sub' => [
                            'Penempatan user interface dialog diatur secara sekuensial',
                            'Setting aktif-pasif komponen user interface dialog disesuaikan dengan urutan alur proses',
                            'Bentuk style dari komponen user interface ditentukan',
                            'Penerapan simulasi dijadikan suatu proses yang sesungguhnya'
                        ]
                    ]
                ]
            ],
            3 => [
                'judul' => 'Melakukan Instalasi Software Tools Pemrograman',
                'unit' => 3,
                'pertanyaan' => [
                    [
                        'judul' => 'Memilih tools pemrograman yang sesuai dengan kebutuhan',
                        'sub' => [
                            'Platform (lingkungan) yang akan digunakan untuk menjalankan tools pemrograman diidentifikasi sesuai dengan kebutuhan',
                            'Tools bahasa pemrograman dipilih sesuai dengan kebutuhan dan lingkungan pengembangan'
                        ]
                    ],
                    [
                        'judul' => 'Instalasi tool pemrograman',
                        'sub' => [
                            'Tools pemrograman ter-install sesuai dengan prosedur',
                            'Tools pemrograman bisa dijalankan di lingkungan pengembangan yang telah ditetapkan'
                        ]
                    ],
                    [
                        'judul' => 'Menerapkan hasil pemodelan kedalam eksekusi script sederhana',
                        'sub' => [
                            'Script (source code) sederhana dibuat sesuai tools pemrograman yang di-install',
                            'Script dapat dijalankan dengan benar dan menghasilkan keluaran sesuai scenario yang diharapkan'
                        ]
                    ]
                ]
            ],
            4 => [
                'judul' => 'Menerapkan coding guidelines dan best practices dalam penulisan program (kode sumber)',
                'unit' => 4,
                'pertanyaan' => [
                    [
                        'judul' => 'Menerapkan Coding Standards',
                        'sub' => [
                            'Kode sumber dituliskan mengikuti coding-guidelines dan best practices',
                            'Struktur program yang sesuai dengan konsep paradigmanya dibuat',
                            'Galat/error ditangani'
                        ]
                    ],
                    [
                        'judul' => 'Menggunakan ukuran performansi dalam menuliskan kode sumber',
                        'sub' => [
                            'Efisiensi penggunaan resources oleh kode dihitung',
                            'Kemudahan interaksi selalu diimplementasikan sesuai standar yang berlaku'
                        ]
                    ]
                ]
            ],
            5 => [
                'judul' => 'Mengimplementasikan Pemrograman Terstruktur',
                'unit' => 5,
                'pertanyaan' => [
                    [
                        'judul' => 'Membuat Program Sederhana',
                        'sub' => [
                            'Program baca tulis untuk memasukkan data dari keyboard dan menampilkan ke layar monitor termasuk variasinya sesuai standar masukan/keluaran telah dibuat',
                            'Struktur kontrol percabangan dan pengulangan dalam membuat program telah digunakan'
                        ]
                    ],
                    [
                        'judul' => 'Membuat program menggunakan prosedur dan fungsi',
                        'sub' => [
                            'Program dengan menggunakan prosedur dibuat sesuai aturan penulisan program',
                            'Program dengan menggunakan fungsi dibuat sesuai aturan penulisan program',
                            'Program dengan menggunakan prosedur dan fungsi secara bersamaan dibuat sesuai aturan penulisan program',
                            'Keterangan untuk setiap prosedur dan fungsi telah diberikan'
                        ]
                    ],
                    [
                        'judul' => 'Membuat program menggunakan array',
                        'sub' => [
                            'Dimensi array telah ditentukan',
                            'Tipe data array telah ditentukan',
                            'Panjang array telah ditentukan',
                            'Pernyataan array telah digunakan'
                        ]
                    ],
                    [
                        'judul' => 'Membuat program untuk akses file',
                        'sub' => [
                            'Program untuk membuat data dalam media penyimpan telah dibuat',
                            'Program untuk membaca data dari media penyimpan telah dibuat'
                        ]
                    ],
                    [
                        'judul' => 'Men-debug program',
                        'sub' => [
                            'Kesalahan program telah dikoreksi',
                            'Kesalahan syntax dalam program telah dibetulkan'
                        ]
                    ]
                ]
            ],
            6 => [
                'judul' => 'Menggunakan Library atau Komponen Pre-existing',
                'unit' => 6,
                'pertanyaan' => [
                    [
                        'judul' => 'Melakukan pemilihan unit-unit reuse yang potensial',
                        'sub' => [
                            'Class unit-unit reuse (dari aplikasi lain) yang sesuai dapat diidentifikasi',
                            'Keuntungan efisiensi dari pemanfaatan komponen reuse dapat dihitung',
                            'Lisensi, Hak cipta dan hak paten tidak dilanggar dalam pemanfaatan komponen reuse tersebut'
                        ]
                    ],
                    [
                        'judul' => 'Melakukan integrasi library atau komponen pre-existing dengan source code yang ada',
                        'sub' => [
                            'Ketergantungan antar unit diidentifikasi',
                            'Penggunaan komponen yang sudah obsolete dihindari',
                            'Program yang dihubungkan dengan library diterapkan'
                        ]
                    ],
                    [
                        'judul' => 'Melakukan pembaharuan library atau komponen pre-existing yang digunakan',
                        'sub' => [
                            'Cara-cara pembaharuan library atau komponen pre-existing diidentifikasi',
                            'Pembaharuan library atau komponen pre-existing berhasil dilakukan'
                        ]
                    ]
                ]
            ],
            7 => [
                'judul' => 'Membuat Dokumen Kode Program',
                'unit' => 7,
                'pertanyaan' => [
                    [
                        'judul' => 'Melakukan identifikasi kode program',
                        'sub' => [
                            'Modul program diidentifikasi',
                            'Parameter yang dipergunakan diidentifikasi',
                            'Algoritma dijelaskan cara kerjanya',
                            'Komentar setiap baris kode termasuk data, eksepsi, fungsi, prosedur dan class (bila ada) diberikan'
                        ]
                    ],
                    [
                        'judul' => 'Membuat dokumentasi modul program',
                        'sub' => [
                            'Dokumentasi modul dibuat sesuai dengan identitas untuk memudahkan pelacakan',
                            'Identifikasi dokumentasi diterapkan',
                            'Kegunaan modul dijelaskan',
                            'Dokumen direvisi sesuai perubahan kode program'
                        ]
                    ],
                    [
                        'judul' => 'Membuat dokumentasi fungsi, prosedur atau method program',
                        'sub' => [
                            'Dokumentasi fungsi, prosedur atau metod dibuat',
                            'Kemungkinan eksepsi dijelaskan',
                            'Dokumen direvisi sesuai perubahan kode program'
                        ]
                    ],
                    [
                        'judul' => 'Men-generate dokumentasi',
                        'sub' => [
                            'Tools untuk generate dokumentasi diidentifikasi',
                            'Generate dokumentasi dilakukan'
                        ]
                    ]
                ]
            ],
            8 => [
                'judul' => 'Melakukan Debugging',
                'unit' => 8,
                'pertanyaan' => [
                    [
                        'judul' => 'Menyiapkan debugging',
                        'sub' => [
                            'Kode program sesuai spesifikasi disiapkan',
                            'Debugging tools untuk melihat proses suatu modul dipersiapkan'
                        ]
                    ],
                    [
                        'judul' => 'Melakukan debugging',
                        'sub' => [
                            'Kode program dikompilasi sesuai bahasa pemrograman yang digunakan',
                            'Penggunaan komponen yang sudah obsolete dihindari',
                            'Kriteria eksekusi aplikasi dianalisis',
                            'Kode kesalahan dicatat'
                        ]
                    ],
                    [
                        'judul' => 'Memperbaiki Program',
                        'sub' => [
                            'Perbaikan terhadap kesalahan kompilasi maupun build dirumuskan',
                            'Perbaikan dilakukan'
                        ]
                    ]
                ]
            ],
        ];
        
        $totalSkema = count($skemaList);
        $currentSkema = $skemaList[$currentPage] ?? $skemaList[1];
    @endphp

    <div class="flex min-h-screen">
        
        {{-- Panggil Component Sidebar --}}
        <x-sidebar2
            backUrl="/tracker"
            :asesorNama="$asesor['nama']"
            :asesorNoReg="$asesor['no_reg']"
        />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Pra - Asesmen</h1>
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">{{ $currentSkema['judul'] }}</h2>
                <p class="text-sm text-gray-500 mb-10">Unit Kompetensi {{ $currentSkema['unit'] }} dari {{ $totalSkema }}</p>

                <div class="shadow border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">
                                    Dapatkah saya .....?
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    K
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    BK
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-32">
                                    Bukti
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            @foreach($currentSkema['pertanyaan'] as $index => $pertanyaan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex">
                                        <span class="font-bold text-gray-800 mr-4">{{ $index + 1 }}</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $pertanyaan['judul'] }}</p>
                                            <ul class="list-decimal list-outside pl-5 mt-2 text-xs text-gray-600 space-y-1">
                                                @foreach($pertanyaan['sub'] as $sub)
                                                <li>{{ $sub }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="radio" name="jawaban_{{ $index + 1 }}" value="k" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="radio" name="jawaban_{{ $index + 1 }}" value="bk" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="text-xs font-medium text-blue-600 hover:text-blue-800 cursor-pointer">
                                        Pilih File
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex justify-between items-center mt-12">
                    @if($currentPage > 1)
                        <a href="?page={{ $currentPage - 1 }}" class="px-8 py-3 bg-gray-300 text-gray-700 font-semibold rounded-full hover:bg-gray-400 shadow-md transition-colors">
                            Sebelumnya
                        </a>
                    @else
                        <button disabled class="px-8 py-3 bg-gray-200 text-gray-400 font-semibold rounded-full cursor-not-allowed shadow-md">
                            Sebelumnya
                        </button>
                    @endif

                    @if($currentPage < $totalSkema)
                        <a href="?page={{ $currentPage + 1 }}" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Selanjutnya
                        </a>
                    @else
                        <button class="px-8 py-3 bg-green-500 text-white font-semibold rounded-full hover:bg-green-600 shadow-md transition-colors">
                            Selesai
                        </button>
                    @endif
                </div>

            </div>
        </main>

    </div>

</body>
</html>