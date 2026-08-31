<?php

namespace Database\Seeders;

use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $comprador = User::inRandomOrder()->first();
            $produtos = Produto::inRandomOrder()->take(rand(1, 3))->get();

            $compra = Compra::create([
                'user_id' => $comprador->id,
                'valor_total' => 0,
            ]);

            $valorTotal = 0;

            foreach ($produtos as $produto) {
                $quantidade = rand(1, 2);

                CompraItem::create([
                    'compra_id' => $compra->id,
                    'produto_id' => $produto->id,
                    'vendedor_id' => $produto->user_id,
                    'quantidade' => $quantidade,
                ]);

                $valorTotal += $produto->preco * $quantidade;
            }

            $compra->update(['valor_total' => $valorTotal]);
        }
    }
}
