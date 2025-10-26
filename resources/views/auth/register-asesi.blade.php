<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

        <div class="w-full md:mx-auto max-w-5xl bg-white rounded-3xl border border-gray-300 shadow-md flex flex-col">

            <div class="flex w-full">
                <div class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tl-3xl
                             bg-white text-blue-600 border-b-[3px] border-blue-600">
                    Asesi
                </div>
                <a href="{{ route('register') }}"
                   class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tr-3xl
                            bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200
                            transition-colors duration-150">
                    Asesor
                </a>
            </div>

            <div class="p-10 md:p-12">

                <div>
                    <a href="/">
                        <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                    </a>
                </div>

                <div class="mt-6 mb-8">
                    <h1 class="text-3xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesi</h1>
                </div>

                <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <input type="hidden" name="role" value="asesi">

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Pribadi</h2>
                            <hr class="mt-2">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_lengkap" name="nama_lengkap" label="Nama Lengkap" :error="$errors->first('nama_lengkap')" required />

                            <x-login-form-input id="nik" name="nik" label="NIK" :error="$errors->first('nik')" required />

                            <x-login-form-input id="tempat_lahir" name="tempat_lahir" label="Tempat Tanggal Lahir" placeholder="Kota" :error="$errors->first('tempat_lahir')" required />

                            <div>
                                <label class="invisible block text-xs font-medium text-gray-800">.</label>
                                <div class="flex items-center gap-3 mt-1">
                                    <select name="tgl_lahir" class="block w-full border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm">
                                        <option value="">Tanggal</option>
                                    </select>
                                    <select name="bln_lahir" class="block w-full border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm">
                                        <option value="">Bulan</option>
                                    </select>
                                    <select name="thn_lahir" class="block w-full border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm">
                                        <option value="">Tahun</option>
                                    </select>
                                </div>
                            </div>

                            <x-login-form-input id="jenis_kelamin" name="jenis_kelamin" label="Jenis Kelamin" :error="$errors->first('jenis_kelamin')" required />

                            <x-login-form-input id="kebangsaan" name="kebangsaan" label="Kebangsaan" :error="$errors->first('kebangsaan')" />

                            <x-login-form-input id="kualifikasi" name="kualifikasi" label="Kualifikasi Pendidikan" :error="$errors->first('kualifikasi')" required />

                            <x-login-form-input id="pekerjaan" name="pekerjaan" label="Pekerjaan" :error="$errors->first('pekerjaan')" required />
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
                        </div>
                    </div>

                    <div class="space-y-5">
                         <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Pekerjaan Sekarang</h2>
                            <hr class="mt-2">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_lembaga" name="nama_lembaga" label="Nama Lembaga" :error="$errors->first('nama_lembaga')" required />
                            <x-login-form-input id="alamat_kantor_1" name="alamat_kantor_1" label="Alamat Kantor" :error="$errors->first('alamat_kantor_1')" required />
                            <x-login-form-input id="jabatan" name="jabatan" label="Jabatan" :error="$errors->first('jabatan')" required />
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
                        Daftar
                    </x-login-button-biru>
                </div>

            </div> </div> </div>
</x-register-layout>
