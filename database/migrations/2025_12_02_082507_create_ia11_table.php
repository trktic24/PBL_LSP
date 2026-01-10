use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ia11', function (Blueprint $table) {
            $table->bigIncrements('id_ia11');

            // SATU ASERI = SATU IA11 (WAJIB)
            $table->unsignedBigInteger('id_data_sertifikasi_asesi')->unique();

            $table->string('nama_produk')->nullable();
            $table->longText('rancangan_produk')->nullable();
            $table->string('standar_industri')->nullable();
            $table->date('tanggal_pengoperasian')->nullable();
            $table->string('gambar_produk')->nullable();
            $table->string('rekomendasi',50)->nullable();

            $table->timestamps();

            // Foreign Key (ANTI DATA YATIM)
            $table->foreign('id_data_sertifikasi_asesi')
                ->references('id_data_sertifikasi_asesi')
                ->on('data_sertifikasi_asesi')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia11');
    }
};
