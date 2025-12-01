@section('title', 'Lupa Password')
@section('description', 'Reset password akun LSP Polines Anda.')
<x-register-layout :backUrl="route('login')">
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200 shadow-[0_8px_24px_rgba(0,0,0,0.05)]">

        <div class="p-10 md:p-12">

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <h1 class="text-2xl font-semibold">Lupa Password?</h1>
                <p class="text-gray-600 mt-2">
                    Masukkan email Anda, kami akan mengirimkan link untuk reset password.
                </p>

                {{-- Input Email --}}
                <div class="mt-4">
                    <x-login-form-input
                        id="email"
                        name="email"
                        type="email"
                        label="Alamat Email"
                        value="{{ old('email') }}"
                        error="{{ $errors->first('email') }}"
                        required
                        autofocus
                    />
                </div>

                {{-- Tombol Submit --}}
                <div class="flex items-center justify-end mt-4">
                    <x-login-button-biru type="submit" class="w-full">
                        Kirim Link Reset Password
                    </x-login-button-biru>
                </div>
            </form>

        </div>
    </div>
</x-register-layout>
