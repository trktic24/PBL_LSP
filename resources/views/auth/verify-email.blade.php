@section('title', 'Verifikasi Email')
@section('description', 'Verifikasi alamat email akun LSP Polines Anda.')
<x-register-layout :backUrl="route('login')">
    {{-- Kita bungkus pakai card putih yang sama kayak 'forgot-password' --}}
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-200 shadow-[0_8px_24px_rgba(0,0,0,0.05)]">
        <div class="p-10 md:p-12">

            <h1 class="text-2xl font-semibold">Verifikasi Email Anda</h1>
            <p class="text-gray-600 mt-2 mb-4">
                Satu langkah lagi! Sebelum lanjut, cek email Anda untuk link verifikasi. Jika tidak ada, kami bisa kirim ulang.
            </p>

            {{-- Status kalo email baru aja dikirim ulang --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    Link verifikasi baru telah dikirim ke alamat email Anda.
                </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row items-center gap-4">
                {{-- Tombol Kirim Ulang --}}
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <x-login-button-biru type="submit" class="w-full">
                        Kirim Ulang Email Verifikasi
                    </x-login-button-biru>
                </form>

                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 px-4 rounded-full text-gray-700 font-semibold bg-gray-100 hover:bg-gray-200 transition-colors">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-register-layout>
