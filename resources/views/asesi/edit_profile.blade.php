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

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                
                <div class="md:flex">
                    <!-- Left Side: Profile Card -->
                    <div class="w-full md:w-1/3 bg-blue-600 p-8 text-white flex flex-col items-center justify-center text-center">
                        <div class="relative mb-6">
                            <div class="h-32 w-32 rounded-full bg-white text-blue-600 flex items-center justify-center text-5xl font-bold shadow-lg border-4 border-blue-200">
                                @php
                                    $user = Auth::user();
                                    $nama = $user->asesi->nama_lengkap ?? $user->username;
                                    $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => substr($w,0,1))->join('');
                                @endphp
                                {{ strtoupper($initials) }}
                            </div>
                        </div>
                        <h2 class="text-xl font-bold mb-1">{{ $nama }}</h2>
                        <p class="text-blue-100 text-sm mb-4">{{ $user->email }}</p>
                        <div class="bg-blue-700 rounded-full px-4 py-1 text-xs font-semibold">
                            Role: {{ ucfirst($user->role->nama_role ?? 'Asesi') }}
                        </div>
                    </div>

                    <!-- Right Side: Form -->
                    <div class="w-full md:w-2/3 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800">Edit Profile</h3>
                            <button @click="editMode = !editMode" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <span x-text="editMode ? 'Batal' : 'Edit Data'"></span>
                            </button>
                        </div>

                        <form action="{{ route('asesi.profile.update') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <input name="username" type="text" value="{{ old('username', $user->username) }}" 
                                       :readonly="!editMode"
                                       :class="editMode ? 'border-blue-500 bg-white ring-2 ring-blue-100' : 'border-gray-200 bg-gray-50 text-gray-500'"
                                       class="w-full rounded-lg border px-4 py-2 focus:outline-none transition-all">
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input name="email" type="email" value="{{ old('email', $user->email) }}" 
                                       :readonly="!editMode"
                                       :class="editMode ? 'border-blue-500 bg-white ring-2 ring-blue-100' : 'border-gray-200 bg-gray-50 text-gray-500'"
                                       class="w-full rounded-lg border px-4 py-2 focus:outline-none transition-all">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="editMode" class="flex justify-end pt-4" style="display: none;">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-md transition">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        <!-- Change Password Section -->
                        <div class="mt-8 pt-8 border-t border-gray-100">
                             <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-800">Keamanan</h4>
                                    <p class="text-xs text-gray-500">Ganti password akun anda</p>
                                </div>
                                <button @click="passwordModal = true" class="text-gray-600 hover:text-blue-600 text-sm font-medium flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-1.5 hover:bg-gray-50 transition">
                                    <i class="fas fa-key"></i> Ganti Password
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

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
