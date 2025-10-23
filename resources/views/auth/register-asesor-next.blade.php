<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

        <div class="w-full max-w-4xl bg-white rounded-3xl border border-gray-300 shadow-md flex flex-col">

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

            <div class="p-10 md:p-12">

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
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-blue-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">1</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-blue-600">Personal Info</h6>
                                <p class="text-xs text-gray-500">Completed</p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex items-center w-full">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-blue-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">2</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-blue-600">Education</h6>
                                <p class="text-xs text-gray-500">Completed</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-gray-300 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">3</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="text-sm font-semibold text-blue-600">Review</h6>
                                <p class="text-xs text-gray-500">Pending</p>
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

                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    KTP <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Foto <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    NPWP <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Rekening <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Curriculum Vitae(CV) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Ijazah Pendidikan <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Sertifikat Asesor Kompetensi <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Sertifikat Kompetensi <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 border border-b-gray-300 rounded-lg">
                                <label for="ktp_file" class="text-base font-medium text-gray-900">
                                    Scan Tanda Tangan <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">Tidak ada berkas yang diupload</span>
                                    <input type="file" name="ktp_file" id="ktp_file" class="hidden">
                                    <label for="ktp_file" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
                                        Pilih File
                                    </label>
                                </div>
                            </div>


                        </div> </div>
                </form>
                <div class="flex items-center justify-between mt-10">
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition-colors">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Kembali
                    </a>

                    <x-login-button-biru type="submit" form="register-form" class="px-10 py-3">
                        Selanjutnya
                    </x-login-button-biru>
                </div>

            </div> </div> </div>
</x-registe>
