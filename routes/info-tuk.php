use Illuminate\Support\Facades\Route;

Route::get('/info-tuk', function () {
    // Memanggil folder 'info_tuk' lalu file 'info-tuk.blade.php'
    return view('info_tuk.info-tuk');
});