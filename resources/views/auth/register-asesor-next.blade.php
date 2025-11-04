<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

        <div class="w-full md:mx-auto max-w-5xl bg-white rounded-3xl border border-gray-200 shadow-md flex flex-col">

            <div class="flex w-full">
                <a href="{{ route('register') }}" class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tl-3xl
                            bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200
                            transition-colors duration-150">
                    Asesi
                </a>
                <div class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tr-3xl
                             bg-white text-blue-600 border-b-[3px] border-blue-600">
                    Asesor
                </div>
            </div>

            <div class="p-10 md:p-12 max-w-4xl mx-auto w-full">

                <div>
                    <a href="/">
                        <img src="{{ asset('images\Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                    </a>
                </div>

                <div class="mt-6 mb-8"> <h1 class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>
                </div>
                <div class="mt-6 mb-8">
                    <div class="flex items-start max-w-screen-lg mx-auto">
                        <div class="w-full">
                            <div class="flex items-center w-full">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-green-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">1</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-green-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-green-600">Informasi Akun</h6>
                                <p class="text-xs text-green-500">Selesai</p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex items-center w-full">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-green-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">2</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-green-600">Informasi Pribadi</h6>
                                <p class="text-xs text-green-500">Selesai</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-blue-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">3</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="text-sm font-semibold text-blue-600">Kelengkapan Dokumen</h6>
                                <p class="text-xs text-gray-500">Sedang diisi</p>
                            </div>
                        </div>
                    </div>
                </div>


                <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <input type="hidden" name="role" value="asesor">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Kelengkapan Dokumen</h2>
                        <p class="text-sm text-gray-500 mt-1">Unggah dokumen dalam format .pdf, .jpg, atau .png. Maksimal ukuran per file adalah 5MB.</p>

                        <div class="mt-6 space-y-4">

                            <x-file-input id="ktp" name="ktp" label="KTP" required/>

                            <x-file-input id="pas_foto" name="pas_foto" label="Foto" required />

                            <x-file-input id="npwp_file" name="npwp_file" label="NPWP" required />

                            <x-file-input id="rekening_file" name="rekening_file" label="Rekening" required />

                            <x-file-input id="cv_file" name="cv_file" label="Curriculum Vitae (CV)" required />

                            <x-file-input id="ijazah_file" name="ijazah_file" label="Ijazah Pendidikan" required />

                            <x-file-input id="sertifikat_asesor_file" name="sertifikat_asesor_file" label="Sertifikat Asesor Kompetensi" required />

                            <x-file-input id="sertifikat_kompetensi_file" name="sertifikat_kompetensi_file" label="Sertifikat Kompetensi" required />

                            <x-file-input id="ttd_file" name="ttd_file" label="Scan Tanda Tangan" required />


                        </div> </div>
                </form>
                <div class="flex items-center justify-between mt-10">
                    <a href="{{ route('register.asesor') }}"
                       class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition-colors">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Kembali
                    </a>

                    <x-login-button-biru type="submit" form="register-form" class="px-10 py-3">
                        Kirim Pendaftaran
                    </x-login-button-biru>
                </div>

            </div> </div> </div>
</x-register-layout>
