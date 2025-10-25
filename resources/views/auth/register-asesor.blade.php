<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

        <div class="w-full md:mx-auto max-w-5xl bg-white rounded-3xl border border-gray-300 shadow-md flex flex-col">

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
                        <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                    </a>
                </div>



                <div class="mb-8"> <h1 class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>
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
                                <h6 class="text-sm font-semibold text-blue-600">Informasi Akun</h6>
                                <p class="text-xs text-gray-500">Selesai</p>
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
                                <h6 class="text-sm font-semibold text-blue-600">Informasi Pribadi</h6>
                                <p class="text-xs text-gray-500">Selesai</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <div class="w-7 h-7 shrink-0 mx-[-1px] bg-gray-300 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">3</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="text-sm font-semibold text-blue-600">Kelengkapan Dokumen</h6>
                                <p class="text-xs text-gray-500">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <input type="hidden" name="role" value="asesor">

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Pribadi</h2>
                            <hr class="mt-2">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_lengkap" name="nama_lengkap" label="Nama Lengkap" :error="$errors->first('nama_lengkap')" required />

                            <x-login-form-input id="no_registrasi_asesor" name="no_registrasi_asesor" label="No Registrasi Asesor" :error="$errors->first('no_registrasi_asesor')" required />

                            <x-login-form-input id="nik" name="nik" label="NIK" :error="$errors->first('nik')" required />
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h2>
                            <hr class="mt-2">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="tempat_lahir" name="tempat_lahir" label="Tempat Tanggal Lahir" placeholder="Kota" :error="$errors->first('tempat_lahir')" required />

                            <div>
                            <label class="invisible block text-xs font-medium text-gray-800">.</label>
                            <div class="flex items-center gap-3 mt-1">
                                <input type="number" name="tgl_lahir" placeholder="Tanggal"
                                class="block w-1/3 border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm" min="1" max="31">

                                <select name="bln_lahir"
                                class="block w-1/3 border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm">
                                <option value="">Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                                </select>

                                <input type="number" name="thn_lahir" placeholder="Tahun"
                                class="block w-1/3 border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm" min="1900" max="2025">
                            </div>
                            </div>


                            <x-login-form-input id="jenis_kelamin" name="jenis_kelamin" label="Jenis Kelamin" :error="$errors->first('jenis_kelamin')" required />
                            <x-login-form-input id="pekerjaan" name="pekerjaan" label="Pekerjaan" :error="$errors->first('pekerjaan')" />
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Alamat & Kontak</h2>
                            <hr class="mt-2">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                            <div class="md:col-span-2">
                                <x-login-form-input id="alamat_rumah" name="alamat_rumah" label="Alamat Rumah" :error="$errors->first('alamat_rumah')" required />
                            </div>
                            <x-login-form-input id="kode_pos" name="kode_pos" label="Kode POS" :error="$errors->first('kode_pos')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="kabupaten" name="kabupaten" label="Kabupaten / Kota" :error="$errors->first('kabupaten')" required />
                            <x-login-form-input id="provinsi" name="provinsi" label="Provinsi" :error="$errors->first('provinsi')" required />
                            <x-login-form-input id="no_hp" name="no_hp" label="Nomor HP" :error="$errors->first('no_hp')" required />
                            <x-login-form-input id="email" name="email" type="email" label="E-mail" :error="$errors->first('email')" required />
                            <x-login-form-input id="npwp" name="npwp" label="NPWP" :error="$errors->first('npwp')" />
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Bank</h2>
                            <hr class="mt-2">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_bank" name="nama_bank" label="Nama Bank" :error="$errors->first('nama_bank')" required />
                            <x-login-form-input id="nomor_rekening" name="nomor_rekening" label="Nomor Rekening" :error="$errors->first('nomor_rekening')" required />
                        </div>
                    </div>
                </form>
                <div class="flex items-center justify-between mt-10">
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition-colors">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        Kembali
                    </a>

                    <x-login-button-biru type="submit" form="register-form" class="px-10 py-3">
                        Selanjutnya
                    </x-login-button-biru>
                </div>

            </div> </div> </div>
</x-register-layout>
