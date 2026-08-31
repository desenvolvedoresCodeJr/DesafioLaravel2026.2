<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = ['Notebooks', 'Smartphones', 'Tablets', 'Monitores'];

        foreach ($categorias as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]);
        }

    }
}
