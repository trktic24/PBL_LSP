<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Skema - Informasi Dasar | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar />
        
        <main class="flex-1 flex flex-col items-center pt-10 pb-12 px-4">
            <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

                <div class="flex items-center justify-between mb-10">
                    <a href="{{ route('master_skema') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD SKEMA</h1>
                    <div class="w-[80px]"></div> 
                </div>
                
                <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
                    <div class="flex flex-col items-center text-center w-32">
                        <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">1</div>
                        <p class="mt-2 text-xs font-medium text-blue-600">Informasi Skema</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
                    
                    <div class="flex flex-col items-center text-center w-32">
                        <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">2</div>
                        <p class="mt-2 text-xs font-medium text-gray-500">Kelompok Pekerjaan</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
                    
                    <div class="flex flex-col items-center text-center w-32">
                        <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">3</div>
                        <p class="mt-2 text-xs font-medium text-gray-500">Rute Ujian</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
                    
                    <div class="flex flex-col items-center text-center w-32">
                        <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">4</div>
                        <p class="mt-2 text-xs font-medium text-gray-500">Pertanyaan & Jawaban</p>
                    </div>
                </div>

                <form action="{{ route('add_skema') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-lg mx-auto">
                    @csrf
                    
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Informasi Skema</h2>

                    <div>
                        <label for="nama_skema" class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_skema" name="nama_skema" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                               placeholder="Masukkan Nama Skema" value="{{ old('nama_skema') }}">
                    </div>

                    <div>
                        <label for="tanggal_pelaksanaan" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                               value="{{ old('tanggal_pelaksanaan') }}">
                    </div>

                    <div>
                        <label for="upload_gambar_skema" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Skema <span class="text-red-500">*</span></label>
                        <input type="file" id="upload_gambar_skema" name="upload_gambar_skema" required
                               class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label for="upload_file_skkni" class="block text-sm font-medium text-gray-700 mb-2">Upload File SKKNI (PDF/DOC) <span class="text-red-500">*</span></label>
                        <input type="file" id="upload_file_skkni" name="upload_file_skkni" required
                               class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                  placeholder="Jelaskan deskripsi singkat mengenai skema ini.">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full flex justify-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition text-center">
                            Selanjutnya
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>