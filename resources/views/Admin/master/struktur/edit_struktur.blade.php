<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Struktur Organisasi | LSP Polines</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
<div class="min-h-screen flex flex-col">

    {{-- Navbar --}}
    <x-navbar.navbar-admin/>

    {{-- Content --}}
    <main class="flex justify-center items-start pt-10 px-4">

        <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Struktur Organisasi</h1>
                <p class="text-sm text-gray-500">Perbarui data pengurus atau jabatan organisasi</p>
            </div>

            <form action="{{ route('admin.update_struktur', $organisasi->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama', $organisasi->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                           required>
                </div>

                {{-- Jabatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jabatan
                    </label>
                    <input type="text"
                           name="jabatan"
                           value="{{ old('jabatan', $organisasi->jabatan) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                           required>
                </div>

                {{-- Gambar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Foto
                    </label>

                    @if($organisasi->gambar)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ asset('storage/'.$organisasi->gambar) }}"
                                 class="w-20 h-20 object-cover rounded-full border shadow">
                            <p class="text-sm text-gray-500">
                                Foto saat ini
                            </p>
                        </div>
                    @endif

                    <input type="file"
                           name="gambar"
                           class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                </div>

                {{-- Tombol --}}
                <div class="flex justify-between items-center pt-6 border-t">

                    <a href="{{ route('admin.master_struktur') }}"
                       class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Kembali
                    </a>

                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </main>
</div>
</body>
</html>
