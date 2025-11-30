<x-register-layout>

    <div class="bg-gray-100 w-full flex items-center justify-center py-5">
        <div class="w-full max-w-4xl bg-white rounded-3xl p-10 md:p-12 border border-gray-300 shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                <div class="flex flex-col justify-center">
                    <div>
                        <a href="/">
                            <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-20 w-auto">
                        </a>
                    </div>
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded-md p-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <div class="mt-6 mb-8">
                        <h1 class="font-poppins text-2xl font-semibold text-gray-900 mb-1">Masuk ke Akun Anda</h1>
                        <p class="text-sm text-gray-500">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                Daftar
                            </a>
                        </p>
                    </div>

                    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <x-login-form-input
                            id="email"
                            name="email"
                            type="email"
                            label="Email"
                            :error="$errors->first('email')"
                            :value="old('email')"
                            required
                            autofocus
                        />
                        <div x-data="{ show: false }">
                            <x-login-form-input
                                id="password"
                                name="password"
                                type="password" x-bind:type="show ? 'text' : 'password'" label="Password"
                                :error="$errors->first('password')"
                                required
                                autocomplete="current-password"
                            />
                            <div class="flex justify-between mt-2">
                                <div>
                                    <input id="show_password_checkbox" type="checkbox"
                                       @click="show = !show"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="show_password_checkbox" class="ml-2 text-sm text-gray-600">
                                    Tampilkan Password
                                </label>
                                </div>
                                <div>
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            Lupa Password?
                                        </a>

                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                <div class="hidden md:flex items-center justify-center">
                    <img src="{{ asset('images/ilustrasi-1.jpg') }}" alt="Ilustrasi Login"
                         class="max-w-[200px] mx-auto">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">

                <x-login-button-biru type="submit" form="login-form" class="w-full sm:w-1/2">
                    Masuk ke Akun
                </x-login-button-biru>

                <div class="flex items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="px-3 text-sm text-gray-400 font-medium">OR</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <x-login-button-google href="{{ route('google.login')}}" class=" font-poppins w-full sm:w-1/2">
                    Continue with Google
                </x-login-button-google>
            </div>

        </div>
    </div>
</x-register-layout>
