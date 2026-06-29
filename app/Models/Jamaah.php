<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jamaah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jamaahs';

    /**
     * Attributes yang aman untuk diinput secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'koordinator_id',
        'wilayah_dakwah',
        'nama_lengkap',
        'nomor_whatsapp',
        'email',
        'alamat_domisili',
        'status_keanggotaan',
        'status_aktif',
        'skor_edukasi_dakwah',
        'jumlah_binaan_jamaah',
        'kehadiran_halaqah_count',
        'peran_halaqah_terakhir',
        'kondisi_ruhiyah',
        'is_clear_syariah_dasar',
        'total_kontribusi_infak',
        'transaksi_ekonomi_berjamaah_count',
        'status_kemandirian_ekonomi',
        'catatan_murobbi_pembina',
        'terakhir_interaksi_at',
    ];

    /**
     * Type casting data untuk menjamin akurasi Strict Types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'skor_edukasi_dakwah' => 'integer',
        'jumlah_binaan_jamaah' => 'integer',
        'kehadiran_halaqah_count' => 'integer',
        'is_clear_syariah_dasar' => 'boolean',
        'total_kontribusi_infak' => 'decimal:2',
        'transaksi_ekonomi_berjamaah_count' => 'integer',
        'terakhir_interaksi_at' => 'datetime',
    ];

    /**
     * Hubungan koordinasi ke tabel User (Koordinator Wilayah / Agen Pembina)
     */
    public function koordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'koordinator_id');
    }
}
