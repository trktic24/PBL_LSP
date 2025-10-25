<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-gray-100 w-full flex items-center justify-center py-5">

        <div class="w-full max-w-4xl bg-white rounded-3xl border border-gray-300 shadow-md flex flex-col"
             x-data="{ role: 'asesi' }">

            <div class="flex w-full">
                <button type="button" @click="role = 'asesi'"
                        :class="{
                            'bg-white text-blue-600 border-b-[3px] border-blue-600': role === 'asesi',
                            'bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200': role !== 'asesi'
                        }"
                        class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tl-3xl focus:outline-none transition-colors duration-150">
                    Asesi
                </button>
                <button type="button" @click="role = 'asesor'"
                        :class="{
                            'bg-white text-blue-600 border-b-[3px] border-blue-600': role === 'asesor',
                            'bg-gray-100 text-gray-500 border-b border-gray-300 hover:bg-gray-200': role !== 'asesor'
                        }"
                        class="flex-1 py-4 px-6 text-center font-semibold text-lg rounded-tr-3xl focus:outline-none transition-colors duration-150">
                    Asesor
                </button>
            </div>

            <div class="p-10 md:p-12">

                <div class="mb-8"> <div class="mb-6"> <a href="/">
                            <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                        </a>
                    </div>

                    <div x-show="role === 'asesor'" style="display: none;" class="mb-8"> <div class="flex items-start max-w-screen-lg mx-auto">
                            <div class="w-full">
                                <div class="flex items-center w-full">
                                    <div class="w-7 h-7 shrink-0 mx-[-1px] bg-blue-600 flex items-center justify-center rounded-full">
                                        <span class="text-sm text-white font-semibold">1</span>
                                    </div>
                                    <div class="w-full h-[3px] mx-4 rounded-lg bg-blue-600"></div>
                                </div>
                                <div class="mt-2 mr-4">
                                    <h6 class="text-sm font-semibold text-blue-600">Informasi Akun</h6>
                                    <p class="text-xs text-gray-500">Sedang diisi</p> </div>
                            </div>
                            <div class="w-full">
                                <div class="flex items-center w-full">
                                    <div class="w-7 h-7 shrink-0 mx-[-1px] bg-gray-300 flex items-center justify-center rounded-full">
                                        <span class="text-sm text-gray-500 font-semibold">2</span> </div>
                                    <div class="w-full h-[3px] mx-4 rounded-lg bg-gray-300"></div> </div>
                                <div class="mt-2 mr-4">
                                    <h6 class="text-sm font-semibold text-gray-500">Informasi Pribadi</h6> <p class="text-xs text-gray-500">Belum</p> </div>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <div class="w-7 h-7 shrink-0 mx-[-1px] bg-gray-300 flex items-center justify-center rounded-full">
                                        <span class="text-sm text-gray-500 font-semibold">3</span> </div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="text-sm font-semibold text-gray-500">Kelengkapan Dokumen</h6> <p class="text-xs text-gray-500">Belum</p> </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h1 x-show="role === 'asesi'" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesi</h1>
                        <h1 x-show="role === 'asesor'" style="display: none;" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>

                        <p class="text-sm text-gray-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                Login
                            </a>
                        </p>
                    </div>
                </div> <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12">

                    <div class="flex flex-col justify-center">
                        <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="role" x-model="role">
                            <x-login-form-input
                                id="name" name="name" type="text" label="Nama Lengkap"
                                :error="$errors->first('name')" required autofocus
                            />
                            <x-login-form-input
                                id="email" name="email" type="email" label="Email"
                                :error="$errors->first('email')" required
                            />
                            <div x-data="{ show: false }">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <x-login-form-input
                                        id="password" name="password" type="password"
                                        x-bind:type="show ? 'text' : 'password'" label="Password"
                                        :error="$errors->first('password')" required autocomplete="new-password"
                                    />
                                    <x-login-form-input
                                        id="password_confirmation" name="password_confirmation" type="password"
                                        x-bind:type="show ? 'text' : 'password'" label="Konfirmasi Password"
                                        :error="$errors->first('password_confirmation')" required autocomplete="new-password"
                                    />
                                </div>
                                <div class="flex items-center mt-2">
                                    <input id="show_password_checkbox" type="checkbox"
                                           @click="show = !show"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="show_password_checkbox" class="ml-2 text-sm text-gray-600">
                                        Tampilkan Password
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="hidden md:flex items-center justify-center">
                        <img x-show="role === 'asesi'" src="{{ asset('images/ilustrasi-asesi.svg') }}" alt="Ilustasi Asesi" class="max-w-[300px] mx-auto">
                        <img x-show="role === 'asesor'" style="display: none;" src="{{ asset('images/ilustrasi-asesor.svg') }}" alt="Ilustrasi Asesor" class="max-w-[300px] mx-auto">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                    <x-login-button-biru type="submit" form="register-form" class="w-full sm:w-1/2">
                        Daftar Sekarang
                    </x-login-button-biru>
                    <div class="flex items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="px-3 text-sm text-gray-400 font-medium">OR</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>
                    <x-login-button-google class="w-full sm:w-1/2">
                        Continue with Google
                    </x-login-button-google>
                </div>

            </div> </div> </div>
</x-register-layout>
