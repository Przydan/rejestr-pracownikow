<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word().'.pdf',
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
            'user_id' => User::factory(),
            'category' => fake()->randomElement(['Umowy', 'Szkolenia', 'BHP', 'Inne']),
            'uploaded_by' => User::factory(),
        ];
    }
}
