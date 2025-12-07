<x-app-layout>
    <div class="flex h-screen bg-gray-50 items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-2xl p-8 text-center">
            
            {{-- Icon Checkmark Besar --}}
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Terima Kasih!</h2>
            <p class="text-gray-600 mb-8">
                Anda telah berhasil mengirimkan Umpan Balik dan Catatan Asesmen (AK.03) untuk sertifikasi ini. Data Anda telah kami simpan.
            </p>

            <div class="space-y-4">
                {{-- Tombol Kembali ke Tracker --}}
                {{-- GANTI route('nama.route.tracker') dengan route tracker kamu --}}
                <a href="{{ route('dashboard') }}" class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                    Kembali ke Tracker/Dashboard
                </a>

                {{-- Opsi sekunder (optional) --}}
                <a href="{{ url()->previous() }}" class="block w-full py-3 px-4 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>