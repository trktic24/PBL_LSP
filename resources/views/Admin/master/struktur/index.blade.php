<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struktur Organisasi | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100">
<div class="min-h-screen flex flex-col">

    <x-navbar.navbar-admin/>

    <main class="p-6">

        <h1 class="text-3xl font-bold mb-6">Struktur Organisasi</h1>

        <a href="{{ route('admin.add_struktur') }}"
           class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            + Tambah Struktur
        </a>

        <div class="bg-white rounded shadow p-4">
            <table class="w-full border border-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Jabatan</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
                </thead>

                <tbody>
                @forelse($organisasis as $org)
                    <tr>
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $org->nama }}</td>
                        <td class="border px-4 py-2">{{ $org->jabatan }}</td>
                        <td class="border px-4 py-2 text-center">
                            <a href="{{ route('admin.edit_struktur', $org->id) }}" class="text-blue-600">Edit</a>
                            |
                            <form action="{{ route('admin.delete_struktur', $org->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus?')" class="text-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Belum ada data struktur
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $organisasis->links() }}
            </div>
        </div>

    </main>

</div>
</body>
</html>
