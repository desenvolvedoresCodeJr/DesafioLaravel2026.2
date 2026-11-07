<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'foto' => fake()->imageUrl(640, 480, 'technics', true),
            'nome' => fake()->words(3, true),
            'descricao' => fake()->paragraph(),
            'preco' => fake()->randomFloat(2, 50, 8000),
            'quantidade' => fake()->numberBetween(1, 10),
            'categoria_id' => Categoria::inRandomOrder()->first()?->id ?? Categoria::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
