<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyResponseRequest extends FormRequest
{
    /**
     * This is a public survey form — anyone with the link may submit it.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],

            'teknisi_jadwal' => ['required', 'in:ya,tidak'],
            'teknisi_kualitas_instalasi' => ['required', 'in:baik,cukup,kurang_baik'],
            'teknisi_penampilan' => ['required', 'in:ya,tidak'],
            'teknisi_panduan' => ['required', 'in:ya,tidak'],
            'teknisi_sikap' => ['required', 'in:sangat_baik,baik,cukup,kurang_baik'],

            'sales_penjelasan' => ['required', 'in:jelas,cukup_jelas,tidak_jelas'],
            'sales_bantuan' => ['required', 'in:sangat_membantu,cukup_membantu,tidak_membantu'],
            'sales_respons' => ['required', 'in:sangat_responsif,cukup_responsif,lambat'],
            'sales_sikap' => ['required', 'in:sangat_baik,baik,cukup,kurang_baik'],

            'kepuasan_keseluruhan' => ['required', 'integer', 'between:1,5'],

            'saran' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Human-readable field names for error messages.
     */
    public function attributes(): array
    {
        return [
            'teknisi_jadwal' => 'jadwal kedatangan teknisi',
            'teknisi_kualitas_instalasi' => 'kualitas instalasi',
            'teknisi_penampilan' => 'penampilan teknisi',
            'teknisi_panduan' => 'panduan penggunaan layanan',
            'teknisi_sikap' => 'sikap dan pelayanan teknisi',
            'sales_penjelasan' => 'penjelasan produk dari sales',
            'sales_bantuan' => 'bantuan proses pendaftaran',
            'sales_respons' => 'respons sales',
            'sales_sikap' => 'sikap dan pelayanan sales',
            'kepuasan_keseluruhan' => 'penilaian kepuasan keseluruhan',
            'saran' => 'saran/masukan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'in' => 'Pilihan pada :attribute tidak valid.',
            'between' => ':attribute harus antara :min sampai :max.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }
}
