<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" /> 
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

<div class="h-screen overflow-y-auto">

    <x-navbar.navbar-admin />
    
    <main class="p-8" 
        x-data="{ 
            editMode: false, 
            passwordModal: {{ $errors->updatePassword->any() ? 'true' : 'false' }}, 
            showSuccess: {{ session('status') === 'profile-updated' ? 'true' : 'false' }},
            showPasswordSuccess: {{ session('status') === 'password-updated' ? 'true' : 'false' }},
            // Logic Preview Gambar: Pakai helper getTtdBase64 untuk existing signature
            previewUrl: '{{ (Auth::user()->admin && Auth::user()->admin->tanda_tangan_admin) ? getTtdBase64(Auth::user()->admin->tanda_tangan_admin, null, 'admin') : '' }}'
        }" 
        x-init="
            if (showSuccess) { setTimeout(() => showSuccess = false, 3000) }
            if (showPasswordSuccess) { setTimeout(() => showPasswordSuccess = false, 3000) }
        ">
        
        <div x-show="showSuccess" class="fixed bottom-10 right-10 z-50 bg-green-500 text-white py-3 px-5 rounded-lg shadow-lg flex items-center" style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <p class="text-sm">Profile Admin berhasil diperbarui!</p>
            <button @click="showSuccess = false" class="ml-4 hover:text-green-200"><i class="fas fa-times"></i></button>
        </div>

        <div x-show="showPasswordSuccess" class="fixed bottom-10 right-10 z-50 bg-blue-600 text-white py-3 px-5 rounded-lg shadow-lg flex items-center" style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10">
            <i class="fas fa-key mr-3 text-lg"></i>
            <p class="text-sm">Password berhasil diubah!</p>
            <button @click="showPasswordSuccess = false" class="ml-4 hover:text-blue-200"><i class="fas fa-times"></i></button>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-10 max-w-3xl mx-auto">

            <div class="flex items-center justify-between mb-10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h2 class="text-3xl font-semibold text-gray-800 text-center flex-1">Account Settings</h2>
                <div class="w-[80px]"></div>
            </div>

            <div class="relative flex justify-center mb-10">
                <div class="h-48 w-48 rounded-full bg-blue-600 text-white flex items-center justify-center text-7xl font-bold shadow-md border-4 border-white select-none">
                    @php
                        $displayInitials = 'AD';
                        if (Auth::check()) {
                            // Prioritaskan Nama Admin dari tabel admin, kalau null pake user->name/email
                            $displayName = Auth::user()->admin->nama_admin ?? Auth::user()->name ?? Auth::user()->email;
                            
                            $words = explode(' ', str_replace(['.', '_'], ' ', $displayName));
                            if (count($words) >= 2) {
                                $displayInitials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                $displayInitials = strtoupper(substr($displayName, 0, 2));
                            }
                        }
                    @endphp
                    {{ $displayInitials }}
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="flex items-center">
                    <label for="name" class="w-1/3 text-sm font-medium text-gray-700">Nama Admin</label>
                    
                    {{-- Mengambil data dari relasi user -> admin -> nama_admin --}}
                    <input id="name" name="name" type="text" 
                        value="{{ Auth::user()->admin->nama_admin ?? '' }}"
                        placeholder="Nama belum diisi"
                        :readonly="!editMode"
                        :class="editMode 
                            ? 'flex-1 border border-blue-400 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none' 
                            : 'flex-1 border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-gray-600 cursor-not-allowed'">
                </div>

                <div class="flex items-center">
                    <label for="email" class="w-1/3 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ Auth::user()->email }}" 
                        :readonly="!editMode"
                        :class="editMode 
                            ? 'flex-1 border border-blue-400 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none' 
                            : 'flex-1 border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-gray-600 cursor-not-allowed'">
                </div>

                <div class="flex items-center">
                    <label for="role" class="w-1/3 text-sm font-medium text-gray-700">Role</label>
                    <input id="role" name="role" type="text" 
                        value="{{ Auth::user()->role->nama_role ?? 'N/A' }}" 
                        readonly
                        class="flex-1 border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-gray-600 cursor-not-allowed">
                </div>

                <div class="flex items-center">
                    <label class="w-1/3 text-sm font-medium text-gray-700">Password</label>
                    <button type="button" @click="passwordModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium shadow-md flex items-center space-x-2 transition">
                        <i class="fas fa-key"></i>
                        <span>Ganti Password</span>
                    </button>
                </div>

                <div class="flex items-start pt-2">
                    <label class="w-1/3 text-sm font-medium text-gray-700 pt-2">Tanda Tangan</label>
                    
                    <div class="flex-1">
                        <div class="w-60 h-34 border-2 border-dashed border-gray-300 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden mb-3 relative group">
                            
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Tanda Tangan" class="w-full h-full object-contain">
                            </template>

                            <template x-if="!previewUrl">
                                <span class="text-gray-400 text-xs text-center px-2">Belum ada<br>tanda tangan</span>
                            </template>
                        </div>

                        <div x-show="editMode" x-transition>
                            <input type="file" name="tanda_tangan_admin" 
                                accept="image/png, image/jpeg, image/jpg"
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 cursor-pointer"
                                @change="
                                    const file = $event.target.files[0];
                                    if(file){
                                        const reader = new FileReader();
                                        reader.onload = (e) => previewUrl = e.target.result;
                                        reader.readAsDataURL(file);
                                    }
                                "
                            />
                            <p class="text-xs text-gray-500 mt-1">Format: PNG (Transparan), JPG. Max: 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-100 mt-6">
                    <div x-show="editMode" class="flex gap-2">
                        <button type="button" 
                            @click="editMode = false; window.location.reload()" 
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 font-medium transition">
                            Batal
                        </button>
                    </div>
                    
                    <button 
                        :type="editMode ? 'submit' : 'button'"
                        @click="if (!editMode) { editMode = true; $event.preventDefault(); }"
                        x-text="editMode ? 'Simpan Perubahan' : 'Edit Profile'"
                        :class="editMode 
                            ? 'ml-2 bg-blue-600 text-white px-6 py-2 rounded-md font-medium shadow-md hover:bg-blue-700 transition' 
                            : 'border border-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-blue-500 hover:text-white hover:border-blue-500 transition duration-200 shadow-sm'">
                    </button>
                </div>
            </form>
        </div>

        <div x-show="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8" @click.away="passwordModal = false">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Ganti Password</h3>
                    <button @click="passwordModal = false"><i class="fas fa-times text-lg text-gray-400 hover:text-gray-600"></i></button>
                </div>
                <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @if($errors->updatePassword->has('current_password'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @if($errors->updatePassword->has('password'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="passwordModal = false" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

</body>
</html>