<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function show(string $cep)
    {
        $cep = somente_numeros($cep);

        if (strlen($cep) !== 8) {
            return response()->json(['erro' => 'CEP inválido. Informe 8 dígitos.'], 422);
        }

        $resposta = Http::timeout(5)->get("https://viacep.com.br/ws/{$cep}/json/");

        if ($resposta->failed()) {
            return response()->json(['erro' => 'Não foi possível consultar o ViaCEP.'], 502);
        }

        if ($resposta->json('erro')) {
            return response()->json(['erro' => 'CEP não encontrado.'], 404);
        }

        return response()->json($resposta->json());
    }
}