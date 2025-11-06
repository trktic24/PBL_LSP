<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Skema</title>
</head>
<body>
    <h1>Daftar Skema Sertifikasi</h1>
    <ul>
        <!-- 
          Ini adalah variabel $skemas yang tadi dikirim
          dari Controller (Pelayan) 
        -->
        @foreach ($skemas as $skema)
            <li>
                <!-- Tampilin nama_skema dari tiap baris data -->
                {{ $skema->nama_skema }} ({{ $skema->kode_unit }})
            </li>
        @endforeach
    </ul>
</body>
</html>