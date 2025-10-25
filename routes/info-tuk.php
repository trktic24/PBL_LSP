use Illuminate\Support\Facades\Route;

Route::get('/info-tuk', function () {
    return view('info_tuk'); // Sesuaikan path jika Anda menyimpannya di subfolder
});