<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Struktur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
<div class="min-h-screen flex flex-col">
<x-navbar.navbar-admin/>

<main class="flex justify-center pt-10">
<div class="bg-white p-8 rounded shadow w-full max-w-lg">

<form action="{{ route('admin.add_struktur.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<input name="nama" placeholder="Nama" class="border p-2 w-full mb-3">
<input name="jabatan" placeholder="Jabatan" class="border p-2 w-full mb-3">
<input type="file" name="gambar" class="border p-2 w-full mb-3">

<button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Simpan</button>
</form>

</div>
</main>
</div>
</body>
</html>
