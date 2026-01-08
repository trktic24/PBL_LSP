<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Struktur | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-50 text-gray-800">
<div class="min-h-screen flex flex-col">
    <x-navbar.navbar-admin/>

    <main class="p-6">
        <h2 class="text-3xl font-bold mb-6">Struktur Organisasi</h2>

        <div class="flex justify-between mb-6">
            <form action="{{ route('admin.master_struktur') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / jabatan"
                       class="border p-2 rounded-lg">
            </form>

            <a href="{{ route('admin.add_struktur') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                + Tambah
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Jabatan</th>
                    <th class="p-3">Foto</th>
                    <th class="p-3">Aksi</th>
                </tr>
                </thead>

                <tbody>
                @forelse($organisasis as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $item->nama }}</td>
                        <td class="p-3">{{ $item->jabatan }}</td>
                        <td class="p-3">
                            @if($item->gambar)
                                <img src="{{ asset('storage/'.$item->gambar) }}" class="h-10">
                            @endif
                        </td>
                        <td class="p-3 space-x-2">
                            <a href="{{ route('admin.edit_struktur',$item->id) }}" class="text-yellow-600">Edit</a>
                            <form action="{{ route('admin.delete_struktur',$item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">Data kosong</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>