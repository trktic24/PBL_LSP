<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <div class="w-full md:mx-auto max-w-5xl bg-white rounded-3xl border border-gray-300 shadow-md flex flex-col">

            {{-- Tab Switch --}}
            <div class="flex w-full">
                <a href="{{ route('register') }}"
                   class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tl-3xl
                          bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200 transition-colors">
                    Asesi
                </a>
                <div class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tr-3xl
                            bg-white text-blue-600 border-b-[3px] border-blue-600">
                    Asesor
                </div>
            </div>

            {{-- Content --}}
            <div class="p-10 md:p-12 w-full">
                <div>
                    <a href="/">
                        <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                    </a>
                </div>

                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>
                </div>

                {{-- Stepper --}}
                <div class="mt-6 mb-8">
                    <div class="flex items-start max-w-screen-lg mx-auto">
                        <div class="w-full">
                            <div class="flex items-center w-full">
                                <div class="w-7 h-7 bg-green-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">1</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-green-600">Informasi Akun</h6>
                                <p class="text-xs text-green-500">Selesai</p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex items-center w-full">
                                <div class="w-7 h-7 bg-blue-600 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">2</span>
                                </div>
                                <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                            </div>
                            <div class="mt-2 mr-4">
                                <h6 class="text-sm font-semibold text-blue-600">Informasi Pribadi</h6>
                                <p class="text-xs text-gray-500">Sedang diisi</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <div class="w-7 h-7 bg-gray-300 flex items-center justify-center rounded-full">
                                    <span class="text-sm text-white font-semibold">3</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h6 class="text-sm font-semibold text-blue-600">Kelengkapan Dokumen</h6>
                                <p class="text-xs text-gray-500">Belum diisi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf
                    <input type="hidden" name="role" value="asesor">

                    {{-- Data Pribadi --}}
                    <div class="space-y-5">
                        <h2 class="text-lg font-semibold text-gray-800">Data Pribadi</h2>
                        <hr class="mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_lengkap" name="nama_lengkap" label="Nama Lengkap" :error="$errors->first('nama_lengkap')" required />
                            <x-login-form-input id="nomor_regis" name="nomor_regis" label="No Registrasi Asesor" :error="$errors->first('nomor_regis')" required />
                            <x-login-form-input id="nik" name="nik" label="NIK" :error="$errors->first('nik')" required />
                        </div>
                    </div>

                    {{-- Informasi Pribadi --}}
                    <div class="space-y-5">
                        <h2 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h2>
                        <hr class="mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="tempat_lahir" name="tempat_lahir" label="Tempat Lahir" placeholder="Kota" :error="$errors->first('tempat_lahir')" required />

                            <div x-data="{
                                tanggal: '',
                                formatTanggal(tgl) {
                                    if (!tgl) return '';
                                    const [day, month, year] = tgl.split('-');
                                    return `${year}-${month}-${day}`;
                                }
                            }">
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                <div class="relative max-w-sm">

                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>

                                    <input id="tanggal_lahir"
                                        name="tanggal_lahir"
                                        x-model="tanggal"
                                        datepicker
                                        datepicker-autohide
                                        datepicker-buttons
                                        datepicker-format="dd-mm-yyyy"
                                        type="text"
                                        class="bg-gray-20 border border-gray-300 text-gray-900 text-sm rounded-lg
                                            placeholder-gray-400
                                            focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                        placeholder="Pilih tanggal">
                                </div>

                                <input type="hidden" name="tanggal_lahir_db" :value="formatTanggal(tanggal)">
                            </div>


                            <x-login-form-dropdown
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                label="Jenis Kelamin"
                                placeholder="Pilih jenis kelamin"
                                :error="$errors->first('jenis_kelamin')"
                                :options="[['value' => 1, 'label' => 'Laki-laki'], ['value' => 0, 'label' => 'Perempuan']]"
                                required
                            />
                            <x-login-form-input id="kebangsaan" name="kebangsaan" label="Kebangsaan" :error="$errors->first('kebangsaan')"/>
                            <x-login-form-input id="pekerjaan" name="pekerjaan" label="Pekerjaan" :error="$errors->first('pekerjaan')" required />
                        </div>
                    </div>

                    {{-- Alamat & Kontak --}}
                    <div class="space-y-5">
                        <h2 class="text-lg font-semibold text-gray-800">Alamat & Kontak</h2>
                        <hr class="mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                            <div class="md:col-span-2">
                                <x-login-form-input id="alamat_rumah" name="alamat_rumah" label="Alamat Rumah" :error="$errors->first('alamat_rumah')" required />
                            </div>
                            <x-login-form-input id="kode_pos" name="kode_pos" label="Kode POS" :error="$errors->first('kode_pos')" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="kabupaten_kota" name="kabupaten_kota" label="Kabupaten / Kota" :error="$errors->first('kabupaten_kota')" required />
                            <x-login-form-input id="provinsi" name="provinsi" label="Provinsi" :error="$errors->first('provinsi')" required />
                            <x-login-form-input id="nomor_hp" name="nomor_hp" label="Nomor HP" :error="$errors->first('nomor_hp')" required />
                            <x-login-form-input id="email" name="email" type="email" label="E-mail" :error="$errors->first('email')" required />
                            <x-login-form-input id="npwp" name="npwp" label="NPWP" :error="$errors->first('npwp')" required />
                        </div>
                    </div>

                    {{-- Informasi Bank --}}
                    <div class="space-y-5">
                        <h2 class="text-lg font-semibold text-gray-800">Informasi Bank</h2>
                        <hr class="mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <x-login-form-input id="nama_bank" name="nama_bank" label="Nama Bank" :error="$errors->first('nama_bank')" required />
                            <x-login-form-input id="norek" name="norek" label="Nomor Rekening" :error="$errors->first('norek')" required />
                        </div>
                    </div>
                </form>

                {{-- Button Navigation --}}
                <div class="flex items-center justify-between mt-10">
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </a>

                    <x-login-button-biru type="submit" form="register-form" class="px-10 py-3">
                        Selanjutnya
                    </x-login-button-biru>
                </div>
            </div>
        </div>
    </div>
</x-register-layout>
