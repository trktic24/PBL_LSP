<x-register-layout>
    <div class="flex flex-col items-center justify-center min-h-[70vh] px-6 py-10 bg-white rounded-2xl shadow-md text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 9v3.75l2.25 1.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        <h1 class="text-2xl font-semibold text-gray-800 mb-3">Pendaftaran Terkirim 🎉</h1>
        <p class="text-gray-600 max-w-md">
            Terima kasih telah mendaftar sebagai <span class="font-medium text-blue-600">{{ ucfirst(Auth::user()->role->nama_role ?? 'asesor') }}</span>.<br>
            Akun Anda sedang kami review. Silakan menunggu verifikasi dari admin sebelum bisa login.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-8 w-full max-w-sm">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                Logout dan Kembali
            </button>
        </form>
    </div>
</x-register-layout>
