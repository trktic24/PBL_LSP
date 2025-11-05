@php
    // Bersihin session Google lama biar email gak ke-lock
    if (!request()->has('step') || request('step') == 1) {
        session()->forget('google_register_data');
    }
@endphp
<x-register-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-gray-100 w-full flex items-center justify-center py-5 min-h-screen">

        @php
    // Tentukan role awal berdasarkan input lama (jika ada), atau default 'asesi'
    $initialRole = old('role', 'asesi');

    // Tentukan step awal
    $initialStep = 1;

    // Jika ada error validasi...
    if ($errors->any()) {
        // Cek error spesifik asesor step 3 (file uploads)
        if ($errors->has('ktp_file') || $errors->has('cv_file') || $errors->has('ttd_file')) {
            $initialStep = 3;

        // Cek error spesifik step 2 (asesi atau asesor).
        // Kita pakai field yg unik untuk step 2, misal 'nik' atau 'nama_lengkap'
        } elseif ($errors->has('nik') || $errors->has('nama_lengkap') || $errors->has('no_registrasi_asesor')) {
            $initialStep = 2;

        // Cek error spesifik step 1 (email atau password)
        } elseif ($errors->has('email') || $errors->has('password')) {
            $initialStep = 1;

        // Jika errornya ada tapi bukan di field yg kita cek,
        // dan rolenya bukan 'asesi', kemungkinan error di step 2 asesor
        } elseif (old('role') == 'asesor') {
            $initialStep = 2;
        // Jika rolenya 'asesi' dan errornya bukan di step 1, pasti di step 2
        } elseif (old('role') == 'asesi' && !$errors->has('email') && !$errors->has('password')) {
            $initialStep = 2;
        }
    }
@endphp
        {{--
          ============================================================
          MASTER WRAPPER
          'otak' utamanya ada di sini.
          - role: 'asesi' atau 'asesor'
          - currentStep: 1, 2, atau 3
        --}}
        <div class="w-full max-w-3xl md:max-w-4xl lg:max-w-5xl bg-white rounded-3xl border border-gray-250 shadow-[0_8px_24px_rgba(0,0,0,0.05)] flex flex-col"
    x-data="registerForm"
    x-init="init()">



            {{--
              ============================================================
              TAB SWITCHER (Asesi / Asesor)
              Saat diklik, ganti 'role' dan reset step ke 1.
              ============================================================
            --}}
            <div class="flex w-full">
                <button type="button" @click="role = 'asesi'; currentStep = 1"
                        :class="{
                            'bg-white text-blue-600 border-b-[3px] border-blue-600': role === 'asesi',
                            'bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200': role !== 'asesi'
                        }"
                        class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tl-3xl focus:outline-none transition-colors duration-150">
                    Asesi
                </button>
                <button type="button" @click="role = 'asesor'; currentStep = 1"
                        :class="{
                            'bg-white text-blue-600 border-b-[3px] border-blue-600': role === 'asesor',
                            'bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200': role !== 'asesor'
                        }"
                        class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tr-3xl focus:outline-none transition-colors duration-150">
                    Asesor
                </button>
            </div>

            {{--
              ============================================================
              FORM WRAPPER
              Satu <form> membungkus SEMUA step.
              ============================================================
            --}}
            <form id="register-form" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                {{-- Input 'role' ini nilainya dinamis, diambil dari state 'role' Alpine.js --}}
                <input type="hidden" name="role" x-model="role" value="{{ $role_id ?? session('google_register_data.role_id') }}">
                <input type="hidden" name="is_google_register" value="{{ session()->has('google_register_data') ? '1' : '0' }}">
                <input type="hidden" name="google_id" value="{{ session('google_register_data.google_id') ?? '' }}">
                <input type="hidden" name="email" value="{{ session('google_register_data.email') ?? old('email') }}">

                @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4" role="alert">
        <p class="font-bold">Ada Error Validasi:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
        <p class="font-bold">Terjadi Kesalahan di Server:</p>
        <p>{{ session('error') }}</p>
    </div>
@endif
                <div class="p-10 md:p-12">

                    <div class="mb-8">
                        <div class="mb-6">
                            <a href="/">
                                <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                            </a>
                        </div>

                        {{--
                          ============================================================
                          STEPPER DINAMIS UNTUK ASESI (2 STEPS)
                          Hanya tampil jika role = 'asesi'
                          ============================================================
                        --}}
                        <div x-show="role === 'asesi'" style="display: none;" class="mb-8">
                            <div class="flex items-start max-w-screen-lg mx-auto">
                                {{-- Step 1 --}}
                                <div class="w-full">
                                    <div class="flex items-center w-full">
                                        <div class="w-7 h-7 shrink-0 mx-[-1px] flex items-center justify-center rounded-full"
                                             :class="{ 'bg-green-600': currentStep > 1, 'bg-blue-600': currentStep === 1, 'bg-gray-300': currentStep < 1 }">
                                            <span class="text-sm font-semibold"
                                                  :class="{ 'text-white': currentStep >= 1, 'text-gray-500': currentStep < 1 }">1</span>
                                        </div>
                                        <div class="w-full h-[3px] mx-4 rounded-lg"
                                             :class="{ 'bg-green-600': currentStep > 1, 'bg-blue-600': currentStep === 1, 'bg-gray-300': currentStep < 1 }"></div>
                                    </div>
                                    <div class="mt-2 mr-4">
                                        <h6 class="text-sm font-semibold"
                                            :class="{ 'text-green-600': currentStep > 1, 'text-blue-600': currentStep === 1, 'text-gray-500': currentStep < 1 }">Informasi Akun</h6>
                                    </div>
                                </div>
                                {{-- Step 2 --}}
                                <div>
                                    <div class="flex items-center">
                                        <div class="w-7 h-7 shrink-0 mx-[-1px] flex items-center justify-center rounded-full"
                                             :class="{ 'bg-blue-600': currentStep === 2, 'bg-gray-300': currentStep < 2 }">
                                            <span class="text-sm font-semibold"
                                                  :class="{ 'text-white': currentStep >= 2, 'text-gray-500': currentStep < 2 }">2</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="text-sm font-semibold"
                                            :class="{ 'text-blue-600': currentStep === 2, 'text-gray-500': currentStep < 2 }">Data Pribadi</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--
                          ============================================================
                          STEPPER DINAMIS UNTUK ASESOR (3 STEPS)
                          Hanya tampil jika role = 'asesor'
                          ============================================================
                        --}}
                        <div x-show="role === 'asesor'" style="display: none;" class="mb-8">
                            <div class="flex items-start max-w-screen-lg mx-auto">
                                {{-- Step 1 --}}
                                <div class="w-full">
                                    <div class="flex items-center w-full">
                                        <div class="w-7 h-7 shrink-0 mx-[-1px] flex items-center justify-center rounded-full"
                                             :class="{ 'bg-green-600': currentStep > 1, 'bg-blue-600': currentStep === 1, 'bg-gray-300': currentStep < 1 }">
                                            <span class="text-sm font-semibold"
                                                  :class="{ 'text-white': currentStep >= 1, 'text-gray-500': currentStep < 1 }">1</span>
                                        </div>
                                        <div class="w-full h-[3px] mx-4 rounded-lg"
                                             :class="{ 'bg-green-600': currentStep > 1, 'bg-blue-600': currentStep === 1, 'bg-gray-300': currentStep < 1 }"></div>
                                    </div>
                                    <div class="mt-2 mr-4">
                                        <h6 class="text-sm font-semibold"
                                            :class="{ 'text-green-600': currentStep > 1, 'text-blue-600': currentStep === 1, 'text-gray-500': currentStep < 1 }">Informasi Akun</h6>
                                    </div>
                                </div>
                                {{-- Step 2 --}}
                                <div class="w-full">
                                    <div class="flex items-center w-full">
                                        <div class="w-7 h-7 shrink-0 mx-[-1px] flex items-center justify-center rounded-full"
                                             :class="{ 'bg-green-600': currentStep > 2, 'bg-blue-600': currentStep === 2, 'bg-gray-300': currentStep < 2 }">
                                            <span class="text-sm font-semibold"
                                                  :class="{ 'text-white': currentStep >= 2, 'text-gray-500': currentStep < 2 }">2</span>
                                        </div>
                                        <div class="w-full h-[3px] mx-4 rounded-lg"
                                             :class="{ 'bg-green-600': currentStep > 2, 'bg-blue-600': currentStep === 2, 'bg-gray-300': currentStep < 2 }"></div>
                                    </div>
                                    <div class="mt-2 mr-4">
                                        <h6 class="text-sm font-semibold"
                                            :class="{ 'text-green-600': currentStep > 2, 'text-blue-600': currentStep === 2, 'text-gray-500': currentStep < 2 }">Informasi Pribadi</h6>
                                    </div>
                                </div>
                                {{-- Step 3 --}}
                                <div>
                                    <div class="flex items-center">
                                        <div class="w-7 h-7 shrink-0 mx-[-1px] flex items-center justify-center rounded-full"
                                             :class="{ 'bg-blue-600': currentStep === 3, 'bg-gray-300': currentStep < 3 }">
                                            <span class="text-sm font-semibold"
                                                  :class="{ 'text-white': currentStep >= 3, 'text-gray-500': currentStep < 3 }">3</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="text-sm font-semibold"
                                            :class="{ 'text-blue-600': currentStep === 3, 'text-gray-500': currentStep < 3 }">Kelengkapan Dokumen</h6>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div>
                            <h1 x-show="role === 'asesi'" style="display: none;" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesi</h1>
                            <h1 x-show="role === 'asesor'" style="display: none;" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>

                            <p class="text-sm text-gray-500">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    Login
                                </a>
                            </p>
                        </div>
                    </div>


                    {{--
                      ============================================================
                      STEP 1: INFORMASI AKUN (Untuk Asesi & Asesor)
                      Tampil jika currentStep === 1
                      DIBUNGKUS <fieldset> AGAR BISA DI-DISABLE
                      ============================================================
                    --}}
                    <fieldset x-show="currentStep === 1"  style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12">
                            <div class="flex flex-col justify-center">
                                <div class="space-y-4">

                                    <x-login-form-input
                                        id="email" name="email" type="email" label="Email"
                                        :error="$errors->first('email')"
                                        :value="session('google_register_data.email') ?? old('email')"
                                        :readonly="session()->has('google_register_data')"
                                        required
                                        autofocus
                                    />
                                    <div x-data="{ show: false, pass :'', conf:'' }">
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <x-login-form-input
                                                id="password" name="password" type="password"
                                                x-bind:type="show ? 'text' : 'password'" label="Password"
                                                x-model="pass"
                                                :error="$errors->first('password')" required autocomplete="new-password"
                                            />
                                            <x-login-form-input
                                                id="password_confirmation" name="password_confirmation" type="password"
                                                x-bind:type="show ? 'text' : 'password'" label="Konfirmasi Password"
                                                x-model="conf"
                                                :error="$errors->first('password_confirmation')" required autocomplete="new-password"
                                            />
                                        </div>
                                        <p x-show="conf.length > 0 && pass !== conf"
                                            class="text-sm text-red-600 mt-2"
                                            style="display: none;">
                                            Konfirmasi Password tidak cocok.
                                        </p>
                                        <div class="flex items-center mt-2">
                                            <input id="show_password_checkbox" type="checkbox"
                                                   @click="show = !show"
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="show_password_checkbox" class="ml-2 text-sm text-gray-600">
                                                Tampilkan Password
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden md:flex items-center justify-center">
                                <img x-show="role === 'asesi'" style="display: none;" src="{{ asset('images/ilustrasi-4.jpg') }}" alt="Ilustasi Asesi" class="max-w-[250px] mx-auto">
                                <img x-show="role === 'asesor'" style="display: none;" src="{{ asset('images/ilustrasi-2.jpg') }}" alt="Ilustrasi Asesor" class="max-w-[250px] mx-auto">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                            <x-login-button-biru type="button" @click.prevent="currentStep = 2"  class="w-full sm:w-1/2">
                                Selanjutnya
                            </x-login-button-biru>

                            <div class="flex items-center">
                                <div class="flex-grow border-t border-gray-200"></div>
                                <span class="px-3 text-sm text-gray-400 font-medium">OR</span>
                                <div class="flex-grow border-t border-gray-200"></div>
                            </div>
                            <a :href="'{{ route('google.login') }}?role=' + role"
                                class="font-poppins w-full sm:w-1/2 flex items-center justify-center gap-3 py-3 px-6 bg-white border border-gray-300 rounded-full text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"> <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"></path> <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"></path> <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"></path> <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571l6.19 5.238C39.712 34.462 44 28.135 44 20c0-1.341-.138-2.65-.389-3.917z"></path> </svg>
                                Continue with Google
                            </a>
                        </div>
                    </fieldset>

                    {{--
                      ============================================================
                      STEP 2: ASESI (Data Pribadi)
                      Tampil jika role === 'asesi' DAN currentStep === 2
                      DIBUNGKUS <fieldset> AGAR BISA DI-DISABLE
                      ============================================================
                    --}}
                    <fieldset x-show="role === 'asesi' && currentStep === 2" x-bind:disabled="role !== 'asesi'" style="display: none;">
                        <div class="space-y-8">
                            <div class="space-y-5">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Data Pribadi</h2>
                                    <hr class="mt-2">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <x-login-form-input id="asesi_nama_lengkap" name="nama_lengkap" label="Nama Lengkap" :error="$errors->first('nama_lengkap')" required x-show="role === 'asesi'" :value="old('nama_lengkap') ?? session('google_register_data.name')"/>
                                    <div>
                                        <x-login-form-input id="asesi_nik" name="nik" label="NIK" x-model="nik" :error="$errors->first('nik')" required />
                                        <p x-show="nik.length>0 && nik.length !== 16"
                                            class="text-sm text-red-600 mt-1"
                                            style="display:none;">
                                            NIK harus 16 digit
                                        </p>
                                    </div>

                                    <x-login-form-input id="asesi_tempat_lahir" name="tempat_lahir" label="Tempat Lahir" placeholder="Kota" :error="$errors->first('tempat_lahir')" required />
                                    <div x-data="{ tanggal: '{{ old('tanggal_lahir') }}' }"> {{-- Scope x-data untuk datepicker --}}
                                        <label for="asesi_tanggal_lahir" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                        <div class="relative w-full">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"> <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" /> </svg>
                                            </div>
                                            <input id="asesi_tanggal_lahir" name="tanggal_lahir" x-model="tanggal" datepicker datepicker-autohide datepicker-buttons datepicker-format="dd-mm-yyyy" type="text" class="bg-gray-20 border border-gray-300 text-gray-900 text-sm rounded-lg placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih tanggal">
                                        </div>
                                    </div>
                                    <x-login-form-dropdown
                                        id="asesi_jenis_kelamin"
                                        name="jenis_kelamin"
                                        label="Jenis Kelamin"
                                        placeholder="Pilih jenis kelamin"
                                        :error="$errors->first('jenis_kelamin')"
                                        :options="[
                                            ['value' => 'Laki-laki', 'label' => 'Laki-laki'],
                                            ['value' => 'Perempuan', 'label' => 'Perempuan']
                                        ]"
                                        required
                                    />
                                    <x-login-form-input id="asesi_kebangsaan" name="kebangsaan" label="Kebangsaan" :error="$errors->first('kebangsaan')" />
                                    {{-- 'kualifikasi' di form -> 'pendidikan' di DB --}}
                                    <x-login-form-input id="asesi_kualifikasi" name="kualifikasi" label="Kualifikasi Pendidikan" :error="$errors->first('kualifikasi')" required />
                                    <x-login-form-input id="asesi_pekerjaan" name="pekerjaan" label="Pekerjaan" :error="$errors->first('pekerjaan')" required />
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Alamat & Kontak</h2>
                                    <hr class="mt-2">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                                    <div class="md:col-span-2">
                                        <x-login-form-input id="asesi_alamat_rumah" name="alamat_rumah" label="Alamat Rumah" :error="$errors->first('alamat_rumah')" required />
                                    </div>
                                    <x-login-form-input id="asesi_kode_pos" name="kode_pos" label="Kode POS" :error="$errors->first('kode_pos')" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    {{-- 'kabupaten' di form -> 'kabupaten_kota' di DB --}}
                                    <x-login-form-input id="asesi_kabupaten" name="kabupaten" label="Kabupaten / Kota" :error="$errors->first('kabupaten')" required />
                                    <x-login-form-input id="asesi_provinsi" name="provinsi" label="Provinsi" :error="$errors->first('provinsi')" required />
                                    {{-- 'no_hp' di form -> 'nomor_hp' di DB --}}
                                    <x-login-form-input id="asesi_no_hp" name="no_hp" label="Nomor HP" :error="$errors->first('no_hp')" required />
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Data Pekerjaan Sekarang</h2>
                                    <hr class="mt-2">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    {{-- 'nama_lembaga' di form -> 'nama_institusi_perusahaan' di DB --}}
                                    <x-login-form-input id="asesi_nama_institusi" name="nama_institusi" label="Nama Institusi" :error="$errors->first('nama_institusi')" required />
                                    {{-- 'alamat_kantor_1' di form -> 'alamat_kantor' di DB --}}
                                    <x-login-form-input id="asesi_alamat_institusi" name="alamat_institusi" label="Alamat Institusi" :error="$errors->first('alamat_kantor')" required />
                                    <x-login-form-input id="asesi_jabatan" name="jabatan" label="Jabatan" :error="$errors->first('jabatan')" required />
                                    <x-login-form-input id="kode_pos_institusi" name="kode_pos_institusi" label="Kode Pos Institusi" :error="$errors->first('kode_pos_institusi')" required />
                                    <x-login-form-input id="no_telepon_institusi" name="no_telepon_institusi" label="No Telepon Institusi" :error="$errors->first('no_telepon_institusi')"/>
                                </div>
                            </div>
                        </div>

                        {{-- Navigasi Step 2 Asesi --}}
                        <div class="flex items-center justify-between mt-10">
                            <button type="button" @click.prevent="currentStep = 1"
                                   class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition-colors">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                                Kembali
                            </button>
                            {{-- Tombol Submit Final untuk Asesi --}}
                            @if(session()->has('google_register_data'))
                            <input type="hidden" name="role_id"
                            value="{{ \App\Models\Role::where('nama_role', session('google_register_data.role'))->first()->id_role ?? '' }}">
                            @endif


                            <x-login-button-biru type="submit" class="px-10 py-3">
                                Daftar
                            </x-login-button-biru>
                        </div>
                    </fieldset>

                    {{--
                      ============================================================
                      STEP 2: ASESOR (Data Pribadi)
                      Tampil jika role === 'asesor' DAN currentStep === 2
                      DIBUNGKUS <fieldset> AGAR BISA DI-DISABLE
                      ============================================================
                    --}}
                    <fieldset x-show="role === 'asesor' && currentStep === 2" x-bind:disabled="role !== 'asesor'" style="display: none;">
                        <div class="space-y-8">

                            <div class="space-y-5">
                                <h2 class="text-lg font-semibold text-gray-800">Data Pribadi</h2>
                                <hr class="mt-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <x-login-form-input id="asesor_nama_lengkap" name="nama_lengkap" label="Nama Lengkap" :error="$errors->first('nama_lengkap')" required x-show="role === 'asesor'" :value="old('nama_lengkap') ?? session('google_register_data.name')"/>
                                    <x-login-form-input id="no_registrasi_asesor" name="no_registrasi_asesor" label="No Registrasi Asesor" :error="$errors->first('no_registrasi_asesor')" required />
                                    <x-login-form-input id="asesor_nik" name="nik" label="NIK" :error="$errors->first('nik')" required />
                                </div>
                            </div>

                            <div class="space-y-5">
                                <h2 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h2>
                                <hr class="mt-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <x-login-form-input id="asesor_tempat_lahir" name="tempat_lahir" label="Tempat Lahir" placeholder="Kota" :error="$errors->first('tempat_lahir')" required />
                                    <div x-data="{ tanggal: '{{ old('tanggal_lahir') }}' }"> {{-- Scope x-data untuk datepicker --}}
                                        <label for="asesor_tanggal_lahir" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                        <div class="relative w-full">
                                             <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"> <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" /> </svg>
                                            </div>
                                            <input id="asesor_tanggal_lahir" name="tanggal_lahir" x-model="tanggal" datepicker datepicker-autohide datepicker-buttons datepicker-format="dd-mm-yyyy" type="text" class="bg-gray-20 border border-gray-300 text-gray-900 text-sm rounded-lg placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih tanggal">
                                        </div>
                                    </div>
                                    <x-login-form-dropdown
                                        id="asesor_jenis_kelamin"
                                        name="jenis_kelamin"
                                        label="Jenis Kelamin"
                                        placeholder="Pilih jenis kelamin"
                                        :error="$errors->first('jenis_kelamin')"
                                        :options="[
                                            ['value' => 'Laki-laki', 'label' => 'Laki-laki'],
                                            ['value' => 'Perempuan', 'label' => 'Perempuan']
                                        ]"
                                        required
                                    />
                                    <x-login-form-input id="asesor_kebangsaan" name="asesor_kebangsaan" label="Kebangsaan" :error="$errors->first('pekerjaan')" required/>
                                    <x-login-form-input id="asesor_pekerjaan" name="pekerjaan" label="Pekerjaan" :error="$errors->first('pekerjaan')" required />
                                        <x-login-form-dropdown
                                        id="skema"
                                        name="skema"
                                        label="Pilih Skema Sertifikasi"
                                        :options="[
                                            ['value' => 'web_dev', 'label' => 'Web Developer'],
                                            ['value' => 'uiux', 'label' => 'UI/UX Designer'],
                                            ['value' => 'network', 'label' => 'Network Engineer']
                                        ]"
                                        required
                                    />
                                    </div>
                                </div>
                            </div>
                            {{-- Alamat & Kontak --}}
                            <div class="space-y-5">
                                <h2 class="text-lg font-semibold text-gray-800">Alamat & Kontak</h2>
                                <hr class="mt-2">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                                    <div class="md:col-span-2">
                                        <x-login-form-input id="asesor_alamat_rumah" name="alamat_rumah" label="Alamat Rumah" :error="$errors->first('alamat_rumah')" required />
                                    </div>
                                    <x-login-form-input id="asesor_kode_pos" name="kode_pos" label="Kode POS" :error="$errors->first('kode_pos')" required />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    {{-- 'kabupaten' di form -> 'kabupaten_kota' di DB --}}
                                    <x-login-form-input id="asesor_kabupaten" name="kabupaten" label="Kabupaten / Kota" :error="$errors->first('kabupaten')" required />
                                    <x-login-form-input id="asesor_provinsi" name="provinsi" label="Provinsi" :error="$errors->first('provinsi')" required />
                                    {{-- 'no_hp' di form -> 'nomor_hp' di DB --}}
                                    <x-login-form-input id="asesor_no_hp" name="no_hp" label="Nomor HP" :error="$errors->first('no_hp')" required />
                                    {{-- 'npwp' di form -> 'NPWP' di DB --}}
                                    <x-login-form-input id="npwp" name="npwp" label="NPWP" :error="$errors->first('npwp')" required />
                            </div>
                            {{-- Informasi Bank --}}
                            <div class="space-y-5">
                                <h2 class="text-lg font-semibold text-gray-800">Informasi Bank</h2>
                                <hr class="mt-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <x-login-form-input id="nama_bank" name="nama_bank" label="Nama Bank" :error="$errors->first('nama_bank')" required />
                                    {{-- 'nomor_rekening' di form -> 'norek' di DB --}}
                                    <x-login-form-input id="nomor_rekening" name="nomor_rekening" label="Nomor Rekening" :error="$errors->first('nomor_rekening')" required />
                                </div>
                            </div>
                        </div>

                        {{-- Navigasi Step 2 Asesor --}}
                        <div class="flex items-center justify-between mt-10">
                            <button type="button" @click.prevent="currentStep = 1"
                                   class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition">
                                <i class="fa-solid fa-arrow-left"></i>
                                Kembali
                            </button>
                            {{-- Tombol 'Next' ke Step 3 --}}
                            <x-login-button-biru type="button" @click.prevent="currentStep = 3" class="px-10 py-3">
                                Selanjutnya
                            </x-login-button-biru>
                        </div>
                    </fieldset>

                    {{--
                      ============================================================
                      STEP 3: ASESOR (Kelengkapan Dokumen)
                      Tampil jika role === 'asesor' DAN currentStep === 3
                      DIBUNGKUS <fieldset> AGAR BISA DI-DISABLE
                      ============================================================
                    --}}
                    <fieldset x-show="role === 'asesor' && currentStep === 3" x-bind:disabled="role !== 'asesor'" style="display: none;">
                        <div class="space-y-8">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Kelengkapan Dokumen</h2>
                                <p class="text-sm text-gray-500 mt-1">Unggah dokumen dalam format .pdf, .jpg, atau .png. Maksimal ukuran per file adalah 5MB.</p>
                        <div class="mt-6 space-y-4">
                            <x-file-input id="ktp_file" name="ktp_file" label="KTP" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="foto_file" name="foto_file" label="Pas Foto" required x-bind:disabled="role !== 'asesor'" />
                            <p class="text-xs text-gray-500 mt-1 ms-1 italic">
                                Format yang diizinkan: <span class="font-semibold">.jpg, .png</span>
                            </p>
                            <x-file-input id="npwp_file" name="npwp_file" label="NPWP" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="rekening_file" name="rekening_file" label="Rekening " required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="cv_file" name="cv_file" label="Curriculum Vitae" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="ijazah_file" name="ijazah_file" label="Ijazah Pendidikan" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="sertifikat_asesor_file" name="sertifikat_asesor_file" label="Sertifikat Asesor Kompetensi" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="sertifikat_kompetensi_file" name="sertifikat_kompetensi_file" label="Sertifikat Kompetensi" required x-bind:disabled="role !== 'asesor'"/>
                            <x-file-input id="ttd_file" name="ttd_file" label="Scan Tanda Tangan" required x-bind:disabled="role !== 'asesor'"/>
                            <p class="text-xs text-gray-500 mt-1 ms-1 italic">
                                Format yang diizinkan: <span class="font-semibold">.png</span>
                            </p>
                        </div>
                        </div>
                        </div>

                        {{-- Navigasi Step 3 Asesor --}}
                        <div class="flex items-center justify-between mt-10">
                             <button type="button" @click.prevent="currentStep = 2"
                                   class="flex items-center gap-2 py-3 px-6 bg-gray-400 text-white text-sm font-semibold rounded-full shadow-md hover:bg-gray-500 transition-colors">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                                Kembali
                            </button>
                            {{-- Tombol Submit Final untuk Asesor --}}
                            <x-login-button-biru type="submit" class="px-10 py-3">
                                Kirim Pendaftaran
                            </x-login-button-biru>
                        </div>
                    </fieldset>

                </div> {{-- Akhir dari div.p-10 --}}
            </form> {{-- Akhir dari <form> tunggal --}}
        </div> {{-- Akhir dari div[x-data] --}}
    </div>

    <script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registerForm', () => ({
        role: '{{ request('role') ?? $initialRole }}',
        currentStep: {{ request('step') ?? $initialStep }},
        nik: '{{ old('nik', '') }}',

        // FUNGSI UNTUK MENJAGA SESSION TETAP HIDUP
        keepAlive() {
            // Ping server setiap 5 menit (300000 ms)
            setInterval(() => {
                // Kita fetch ke route yang nanti kita bikin
                fetch('/keep-alive', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'session_refreshed') {
                        console.log('Session refreshed');
                    }
                })
                .catch(error => console.error('Keep-alive ping failed:', error));
            }, 300000);
        },

        init() {
            // 🚀 PANGGIL FUNGSI KEEP-ALIVE DI SINI
            // Ini akan jalan pas halaman pertama kali dibuka
            this.keepAlive();
            document.querySelector('form')?.addEventListener('submit', () => {
                this.clearStorage();
            });
            // 🔁 Load data dari localStorage
            const saved = localStorage.getItem('register_form');
            if (saved) {
                const savedData = JSON.parse(saved);

                Object.entries(savedData).forEach(([key, value]) => {
                    // Kita nggak akan pernah nimpa _token
                    if (key !== '_token') {
                        let el = document.querySelector(`[name="${key}"]`);
                        if (el) el.value = value;
                    }
                });
            }

            // 💾 Auto-save setiap kali input berubah
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => {
                    const data = Object.fromEntries(new FormData(document.querySelector('form')));

                    // Hapus token sebelum nyimpen
                    delete data._token;

                    localStorage.setItem('register_form', JSON.stringify(data));
                });
            });
        },

        clearStorage() {
            localStorage.removeItem('register_form');
        }
    }));
});
</script>
</x-register-layout>
