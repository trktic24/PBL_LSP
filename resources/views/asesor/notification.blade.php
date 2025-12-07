@extends('layouts.app-profil')
@section('content')

<div class="container mx-auto px-6 mt-20 mb-12">
    {{-- Header --}}
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('asesor.home.index') }}" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Semua Notifikasi</h1>
    </div>

    {{-- Content --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden min-h-[500px] flex flex-col">
        <div class="divide-y divide-gray-100 flex-grow py-4">
            @forelse ($notifications as $notification)
            @php
            $data = $notification->data;
            $isRead = !is_null($notification->read_at);
            @endphp

            {{-- Item Notifikasi dengan Efek Premium --}}
            <div class="p-5 mb-3 mx-4 rounded-xl border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200 group {{ !$isRead ? 'bg-gradient-to-r from-blue-50 to-white' : 'bg-white' }}">
                <div class="flex items-start gap-5">
                    {{-- Icon Container --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm transition-all duration-300 {{ !$isRead ? 'bg-blue-600 text-white shadow-blue-200 group-hover:shadow-blue-300 group-hover:scale-110' : 'bg-gray-100 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600' }}">
                            @if(isset($data['icon']) && $data['icon'] == 'warning')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Text --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-blue-700 transition-colors duration-200">
                                {{ $data['title'] ?? 'Notifikasi' }}
                            </h3>
                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed group-hover:text-gray-700">
                            {{ $data['body'] ?? 'Pesan tidak tersedia' }}
                        </p>
                        @if(isset($data['link']) && $data['link'] != '#')
                        <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <a href="{{ $data['link'] }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Dot --}}
                    @if (!$isRead)
                    <div class="flex-shrink-0 self-center">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 shadow-md shadow-red-200"></span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-lg">Belum ada notifikasi.</p>
            </div>
            @endforelse
        </div>

        {{-- Footer / Pagination --}}
        @if($notifications->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-center">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection