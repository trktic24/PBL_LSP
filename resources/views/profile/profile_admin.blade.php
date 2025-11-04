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

    <x-navbar />
    
    <main class="p-8" 
          x-data="{ 
            editMode: false, 
            showSuccess: {{ session('status') === 'profile-updated' ? 'true' : 'false' }} 
          }" 
          x-init="if (showSuccess) { setTimeout(() => showSuccess = false, 3000) }">
        
        <div x-show="showSuccess"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="fixed bottom-10 right-10 z-50 bg-green-500 text-white py-3 px-5 rounded-lg shadow-lg flex items-center"
             role="alert">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <p class="text-sm">Profil berhasil diperbarui!</p>
            <button @click="showSuccess = false" class="ml-4 -mr-1 text-green-100 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-10 max-w-3xl mx-auto">

            <div class="flex items-center justify-between mb-10">
                <a href="{{ route('dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <h2 class="text-3xl font-semibold text-gray-800 text-center flex-1">Account Settings</h2>
                <div class="w-[80px]"></div>
            </div>

            <div class="relative flex justify-center mb-10">
                <div class="relative">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Avatar" 
                         class="w-48 h-48 rounded-full object-cover shadow-md border-4 border-white">
                    <label for="avatar-upload" 
                         class="absolute bottom-2 right-3 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-100 transition">
                        <i class="fas fa-camera text-gray-600"></i>
                    </label>
                    <input id="avatar-upload" type="file" class="hidden">
                </div>

                <button class="absolute bottom-2 right-0 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-sm font-medium shadow-sm flex items-center space-x-1.5 transition">
                    <i class="fas fa-trash-alt text-xs"></i>
                    <span>Delete Avatar</span>
                </button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="flex items-center">
                    <label for="name" class="w-1/3 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="Admin LSP" 
                           :readonly="!editMode"
                           :class="editMode 
                                ? 'flex-1 border border-blue-400 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none' 
                                : 'flex-1 border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-gray-600 cursor-not-allowed'">
                </div>

                <div class="flex items-center">
                    <label for="username" class="w-1/3 text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" value="{{ Auth::user()->username }}" 
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
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium shadow-md flex items-center space-x-2">
                        <i class="fas fa-key"></i>
                        <span>Change Password</span>
                    </button>
                </div>

                <div class="flex justify-end pt-6">
                    <button 
                        :type="editMode ? 'submit' : 'button'"
                        @click="if (!editMode) { editMode = true; $event.preventDefault(); }"
                        x-text="editMode ? 'Save Changes' : 'Edit Profile'"
                        :class="editMode 
                            ? 'bg-blue-500 text-white px-6 py-2 rounded-md font-medium shadow-md hover:bg-blue-600 transition' 
                            : 'border border-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-blue-500 hover:text-white hover:border-blue-500 transition duration-200 shadow-sm'">
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>