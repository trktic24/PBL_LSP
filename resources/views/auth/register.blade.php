<x-register-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12">

                    <div class="flex flex-col justify-center">
                        <div>
                            <a href="/">
                                <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                            </a>
                        </div>

                        <div class="mt-6 mb-8">
                            <h1 x-show="role === 'asesi'" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesi</h1>
                            <h1 x-show="role === 'asesor'" style="display: none;" class="text-2xl font-semibold text-gray-900 mb-1">Daftar sebagai Asesor</h1>

                            <p class="text-sm text-gray-500">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    Login
                                </a>
                            </p>
                        </div>

                        <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="role" x-model="role">

                            <x-login-form-input
                                id="name"
                                name="name"
                                type="text"
                                label="Nama Lengkap"
                                :error="$errors->first('name')"
                                required
                                autofocus
                            />

                            <x-login-form-input
                                id="email"
                                name="email"
                                type="email"
                                label="Email"
                                :error="$errors->first('email')"
                                required
                            />

                            <div class="flex flex-col md:flex-row gap-4">
                                <x-login-form-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    label="Password"
                                    :error="$errors->first('password')"
                                    required
                                    autocomplete="new-password"
                                />

                                <x-login-form-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    label="Konfirmasi Password"
                                    :error="$errors->first('password_confirmation')"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <div class="flex items-center">
                                <input id="show_password_checkbox" type="checkbox"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="show_password_checkbox" class="ml-2 text-sm text-gray-600">
                                    Tampilkan Password
                                </label>
                            </div>
                        </form>
                    </div>

                    <div class="hidden md:flex items-center justify-center">
                        <img x-show="role === 'asesi'" src="{{ asset('images/ilustrasi-asesi.svg') }}" alt="Ilustasi Asesi" class="max-w-[300px] mx-auto">
                        <img x-show="role === 'asesor'" style="display: none;" src="{{ asset('images/ilustrasi-asesor.svg') }}" alt="Ilustrasi Asesor" class="max-w-[300px] mx-auto">
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                    <!-- Tombol Login -->
                    <x-login-button-biru type="submit" form="login-form" class="w-full sm:w-1/2">
                        Daftar Sekarang
                    </x-login-button-biru>

                    <div class="flex items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="px-3 text-sm text-gray-400 font-medium">OR</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <!-- Tombol Google -->
                    <x-login-button-google class="w-full sm:w-1/2">
                        Continue with Google
                    </x-login-button-google>
                </div>

            </div> </div> </div>
</x-register-layout>
