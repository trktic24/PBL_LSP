@section('title', 'Konfirmasi Password')
@section('description', 'Konfirmasi password Anda untuk melanjutkan.')
<x-register-layout :backUrl="route('login')">
    {{-- Kita bungkus pakai card putih yang sama --}}
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200 shadow-[0_8px_24px_rgba(0,0,0,0.05)]">
        <div class="p-10 md:p-12">

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <h1 class="text-2xl font-semibold">Konfirmasi Password</h1>
                <p class="text-gray-600 mt-2 mb-4">
                    Harap konfirmasi password Anda sebelum melanjutkan.
                </p>

                {{-- Input Password --}}
                <div class="mt-4">
                    <x-login-form-input
                        id="password"
                        name="password"
                        type="password"
                        label="Password"
                        error="{{ $errors->first('password') }}"
                        required
                        autocomplete="current-password"
                        autofocus
                    />
                </div>

                {{-- Tombol Submit --}}
                <div class="flex items-center justify-end mt-4">
                    <x-login-button-biru type="submit" class="w-full">
                        Konfirmasi
                    </x-login-button-biru>
                </div>
            </form>

        </div>
    </div>
</x-register-layout>
