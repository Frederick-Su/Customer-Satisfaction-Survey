<?php

namespace Database\Factories;

use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyResponse>
 */
class SurveyResponseFactory extends Factory
{
    protected $model = SurveyResponse::class;

    public function definition(): array
    {
        return [
            'customer_id' => fake()->optional()->numerify('CUST-######'),
            'teknisi_jadwal' => fake()->randomElement(['ya', 'tidak']),
            'teknisi_kualitas_instalasi' => fake()->randomElement(['baik', 'cukup', 'kurang_baik']),
            'teknisi_penampilan' => fake()->randomElement(['ya', 'tidak']),
            'teknisi_panduan' => fake()->randomElement(['ya', 'tidak']),
            'teknisi_sikap' => fake()->randomElement(['sangat_baik', 'baik', 'cukup', 'kurang_baik']),
            'sales_penjelasan' => fake()->randomElement(['jelas', 'cukup_jelas', 'tidak_jelas']),
            'sales_bantuan' => fake()->randomElement(['sangat_membantu', 'cukup_membantu', 'tidak_membantu']),
            'sales_respons' => fake()->randomElement(['sangat_responsif', 'cukup_responsif', 'lambat']),
            'sales_sikap' => fake()->randomElement(['sangat_baik', 'baik', 'cukup', 'kurang_baik']),
            'kepuasan_keseluruhan' => fake()->numberBetween(1, 5),
            'saran' => fake()->optional()->sentence(),
        ];
    }
}