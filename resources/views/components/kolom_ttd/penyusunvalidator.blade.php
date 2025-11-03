{{-- 
  File: resources/views/components/kolom_ttd/penyusunvalidator.blade.php
  (Ganti seluruh isi file dengan kode ini)
--}}
<div class="border border-gray-900 shadow-md">
    <table class="w-full">
        <thead>
            <tr class="bg-black text-white">
                <th class="border border-gray-900 p-2 font-semibold">STATUS</th>
                <th class="border border-gray-900 p-2 font-semibold">NAMA</th>
                <th class="border border-gray-900 p-2 font-semibold">NOMOR MET</th>
                <th class="border border-gray-900 p-2 font-semibold">TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                {{-- Gunakan rowspan="2" agar PENYUSUN mencakup 2 baris --}}
                <td class="border border-gray-900 p-2 text-sm font-medium bg-black text-white" rowspan="2">PENYUSUN</td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="penyusun_nama_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="penyusun_met_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm h-16"></td>
            </tr>
            <tr>
                {{-- Kolom STATUS tidak perlu diisi lagi karena sudah di-rowspan --}}
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="penyusun_nama_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="penyusun_met_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm h-16"></td>
            </tr>
            <tr>
                {{-- Gunakan rowspan="2" agar VALIDATOR mencakup 2 baris --}}
                <td class="border border-gray-900 p-2 text-sm font-medium bg-black text-white" rowspan="2">VALIDATOR</td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="validator_nama_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="validator_met_1" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm h-16"></td>
            </tr>
            <tr>
                {{-- Kolom STATUS tidak perlu diisi lagi karena sudah di-rowspan --}}
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="validator_nama_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm">
                    <input type="text" name="validator_met_2" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </td>
                <td class="border border-gray-900 p-2 text-sm h-16"></td>
            </tr>
        </tbody>
    </table>
</div>