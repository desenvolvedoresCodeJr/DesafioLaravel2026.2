<?php

use Carbon\Carbon;

if (!function_exists('formatar_dinheiro')) {
    function formatar_dinheiro($valor): string
    {
        return 'R$ ' . number_format((float) $valor, 2, ',', '.');
    }
}

if (!function_exists('formatar_data')) {
    function formatar_data($data, string $formato = 'd/m/Y'): string
    {
        return $data ? Carbon::parse($data)->format($formato) : '';
    }
}

if (!function_exists('somente_numeros')) {
    function somente_numeros(?string $texto): string
    {
        return preg_replace('/\D/', '', (string) $texto);
    }
}

if (!function_exists('usuario_e_admin')) {
    function usuario_e_admin(): bool
    {
        return (bool) auth()->user()?->is_admin;
    }
}

if (!function_exists('url_foto')) {
    function url_foto(?string $foto): ?string
    {
        if (!$foto) {
            return null;
        }

        return str_starts_with($foto, 'http') ? $foto : asset('storage/' . $foto);
    }
}