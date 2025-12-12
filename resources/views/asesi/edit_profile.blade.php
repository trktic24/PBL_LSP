<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" /> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <x-navbar active="profile" />

    <div class="h-screen pt-24 pb-12 px-6">
        <main class="max-w-4xl mx-auto"
            x-data="{ 
                editMode: false, 
                passwordModal: {{ $errors->updatePassword->any() ? 'true' : 'false' }}, 
                showSuccess: {{ session('status') === 'profile-updated' ? 'true' : 'false' }} 
            }" 
            x-init="if (showSuccess) { setTimeout(() => showSuccess = false, 3000) }">

            <!-- Notification -->
            <div x-show="showSuccess"
                 x-transition
                 class="fixed bottom-10 right-10 z-50 bg-green-500 text-white py-3 px-5 rounded-lg shadow-lg flex items-center"
                 style="display: none;">
                <i class="fas fa-check-circle mr-3"></i>
                <p>Profile berhasil diperbarui!</p>
            </div>

            <form action="{{ route('asesi.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="max-w-6xl mx-auto mt-8 bg-white p-8 rounded-lg shadow-md relative pb-24">
                
                    <!-- Foto Profil -->
                    <div class="flex flex-col items-center space-y-2 mb-10">
                        <div class="h-32 w-32 rounded-full bg-white text-blue-600 flex items-center justify-center text-5xl font-bold shadow-lg border-4 border-blue-500 overflow-hidden relative group">
                             @php
                                $user = Auth::user();
                                $nama = $user->asesi->nama_lengkap ?? $user->username;
                                // Simple Initials
                                $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => substr($w,0,1))->join('');
                            @endphp
                            <!-- Placeholder Initials if no image (can be replaced with actual image logic later) -->
                            <span class="z-0">{{ strtoupper($initials) }}</span>
                            
                            {{-- Optional: Upload Overlay --}}
                            <!-- <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </div> -->
                        </div>
                        <h2 class="text-xl font-bold">{{ $nama }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>

                    <!-- Layout Grid 2 Kolom -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-10 w-full">
                        
                        {{-- KOLOM KIRI --}}
                        <div class="flex flex-col space-y-8 w-full">
                            
                            {{-- Section: Data Akun & Pribadi --}}
                            <div>
                                <h2 class="text-lg font-semibold mb-3">Data Pribadi</h2>
                                <div class="space-y-3">
                                    {{-- NIK --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                        <input name="nik" type="text" value="{{ old('nik', $asesi->nik ?? '') }}" 
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Nama --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input name="nama_lengkap" type="text" value="{{ old('nama_lengkap', $asesi->nama_lengkap ?? '') }}"
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- TTL --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                            <input name="tempat_lahir" type="text" value="{{ old('tempat_lahir', $asesi->tempat_lahir ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                            <input name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', optional($asesi->tanggal_lahir ?? null)->format('Y-m-d') ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- Gender & Kebangsaan --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" 
                                                    :disabled="!editMode"
                                                    :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                    class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none disabled:text-gray-600 disabled:opacity-100">
                                                <option value="">Pilih...</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin', $asesi->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin', $asesi->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kebangsaan</label>
                                            <input name="kebangsaan" type="text" value="{{ old('kebangsaan', $asesi->kebangsaan ?? 'Indonesia') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- Pendidikan & Pekerjaan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                                        <input name="pendidikan" type="text" value="{{ old('pendidikan', $asesi->pendidikan ?? '') }}"
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan (Pribadi)</label>
                                        <input name="pekerjaan_pribadi" type="text" value="{{ old('pekerjaan_pribadi', $asesi->pekerjaan ?? '') }}"
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="flex flex-col space-y-8 w-full">
                            
                            {{-- Section: Kontak & Alamat --}}
                            <div>
                                <h2 class="text-lg font-semibold mb-3">Kontak & Alamat</h2>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah</label>
                                        <textarea name="alamat_rumah" rows="3"
                                                  :readonly="!editMode"
                                                  :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                  class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">{{ old('alamat_rumah', $asesi->alamat_rumah ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kab/Kota</label>
                                            <input name="kabupaten_kota" type="text" value="{{ old('kabupaten_kota', $asesi->kabupaten_kota ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                            <input name="provinsi" type="text" value="{{ old('provinsi', $asesi->provinsi ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                            <input name="kode_pos" type="text" value="{{ old('kode_pos', $asesi->kode_pos ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                                            <input name="nomor_hp" type="text" value="{{ old('nomor_hp', $asesi->nomor_hp ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Section: Data Pekerjaan --}}
                            <div class="pt-4 border-t">
                                <h2 class="text-lg font-semibold mb-3">Data Institusi</h2>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Institusi</label>
                                        <input name="nama_institusi_pekerjaan" type="text" value="{{ old('nama_institusi_pekerjaan', $pekerjaan->nama_institusi_pekerjaan ?? '') }}"
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan di Institusi</label>
                                        <input name="jabatan_institusi" type="text" value="{{ old('jabatan_institusi', $pekerjaan->jabatan ?? '') }}"
                                               :readonly="!editMode"
                                               :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                               class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Institusi</label>
                                        <textarea name="alamat_institusi" rows="2"
                                                  :readonly="!editMode"
                                                  :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                  class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">{{ old('alamat_institusi', $pekerjaan->alamat_institusi ?? '') }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Telp Institusi</label>
                                            <input name="no_telepon_institusi" type="text" value="{{ old('no_telepon_institusi', $pekerjaan->no_telepon_institusi ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Pos Institusi</label>
                                            <input name="kode_pos_institusi" type="text" value="{{ old('kode_pos_institusi', $pekerjaan->kode_pos_institusi ?? '') }}"
                                                   :readonly="!editMode"
                                                   :class="editMode ? 'bg-white border-blue-500 ring-1 ring-blue-500' : 'bg-gray-100 border-gray-300'"
                                                   class="w-full border rounded-md px-3 py-2 transition-all focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- TOMBOL ACTION --}}
                    <div class="absolute bottom-6 right-6 flex space-x-4">
                        {{-- Tombol Edit/Simpan Toggle --}}
                        <button type="button" @click="editMode = !editMode; if(!editMode) $el.closest('form').submit()"
                                :class="editMode ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                                class="flex items-center text-white px-5 py-2 rounded-full transition shadow-md">
                            <template x-if="!editMode">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 19H5v-4L15 7z" />
                                    </svg>
                                    Edit
                                </span>
                            </template>
                            <template x-if="editMode">
                                <button type="submit" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan
                                </button>
                            </template>
                        </button>
                    </div>

                </div>
            </form>

            <!-- Modal Change Password -->
            <div x-show="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 style="display: none;" x-transition>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.away="passwordModal = false">
                    <h3 class="text-xl font-bold mb-4">Ganti Password</h3>
                    <form action="{{ route('asesi.profile.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" class="w-full border rounded-lg px-3 py-2">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2">
                            @if($errors->updatePassword->has('password'))
                                <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="passwordModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
