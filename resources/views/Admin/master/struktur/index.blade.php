@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Struktur Organisasi</h1>
            <p class="text-gray-500 text-sm">Kelola data pengurus dan struktur organisasi LSP Polines</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.add_struktur') }}" 
               class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-sm font-medium">
                <i class="fas fa-plus mr-2 text-sm"></i>
                Tambah Anggota
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.master_struktur') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" 
                       placeholder="Cari berdasarkan nama atau jabatan...">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition-all text-sm font-medium">
                    Filter
                </button>
                <a href="{{ route('admin.master_struktur') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-all text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($organisasis as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($item->gambar)
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm" 
                                             src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                            {{ substr($item->nama, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ $item->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-medium text-xs">
                                {{ $item->jabatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.edit_struktur', $item->id) }}" 
                                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                   title="Edit Data">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                                <form action="{{ route('admin.delete_struktur', $item->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $item->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Data">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 font-medium">Belum ada data struktur organisasi.</p>
                                <a href="{{ route('admin.add_struktur') }}" class="text-blue-600 text-sm hover:underline mt-1">Tambah data pertama sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($organisasis->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            {{ $organisasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection