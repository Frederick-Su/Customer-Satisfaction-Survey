<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'no_hp',
        'teknisi_jadwal',
        'teknisi_kualitas_instalasi',
        'teknisi_penampilan',
        'teknisi_panduan',
        'teknisi_sikap',
        'sales_penjelasan',
        'sales_bantuan',
        'sales_respons',
        'sales_sikap',
        'kepuasan_keseluruhan',
        'saran',
    ];

    protected $casts = [
        'kepuasan_keseluruhan' => 'integer',
    ];

    /**
     * Human-readable labels for each choice value, keyed by column.
     * Handy later if you build a results/export view.
     */
    public static function choiceLabels(): array
    {
        return [
            'teknisi_jadwal' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
            'teknisi_kualitas_instalasi' => ['baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
            'teknisi_penampilan' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
            'teknisi_panduan' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
            'teknisi_sikap' => ['sangat_baik' => 'Sangat Baik', 'baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
            'sales_penjelasan' => ['jelas' => 'Jelas', 'cukup_jelas' => 'Cukup Jelas', 'tidak_jelas' => 'Tidak Jelas'],
            'sales_bantuan' => ['sangat_membantu' => 'Sangat Membantu', 'cukup_membantu' => 'Cukup Membantu', 'tidak_membantu' => 'Tidak Membantu'],
            'sales_respons' => ['sangat_responsif' => 'Sangat Responsif', 'cukup_responsif' => 'Cukup Responsif', 'lambat' => 'Lambat'],
            'sales_sikap' => ['sangat_baik' => 'Sangat Baik', 'baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
        ];
    }
}
