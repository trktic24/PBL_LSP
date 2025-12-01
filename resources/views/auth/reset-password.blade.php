@section('title', 'Reset Password')
@section('description', 'Buat password baru untuk akun LSP Polines Anda.')
<x-register-layout> {{-- Ganti ini pake layout lu --}}
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200 shadow-[0_8px_24px_rgba(0,0,0,0.05)]">
        <div class="p-10 md:p-12">

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <h1 class="text-2xl font-semibold">Buat Password Baru</h1>

            <div class="mt-4">
                <x-login-form-input
                    id="email"
                    name="email"
                    type="email"
                    label="Alamat Email"
                    value="{{ old('email', $request->email) }}"
                    error="{{ $errors->first('email') }}"
                    required
                    autofocus
                    readonly {{-- Bikin readonly aja biar aman --}}
                />
            </div>

            <div class="mt-4">
                <x-login-form-input
                    id="password"
                    name="password"
                    type="password"
                    label="Password Baru"
                    error="{{ $errors->first('password') }}"
                    required
                />
            </div>

            <div class="mt-4">
                <x-login-form-input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    label="Konfirmasi Password Baru"
                    error="{{ $errors->first('password_confirmation') }}"
                    required
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-login-button-biru type="submit" class="w-full">
                    Reset Password
                </x-login-button-biru>
            </div>
        </form>
            </div>
    </div>


</x-register-layout>
