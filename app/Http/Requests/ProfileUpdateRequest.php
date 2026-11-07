<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->user()->id;

        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($id)],
            'cep'             => ['required', 'string', 'max:9'],
            'numero'          => ['required', 'string', 'max:20'],
            'logradouro'      => ['required', 'string', 'max:255'],
            'bairro'          => ['required', 'string', 'max:255'],
            'cidade'          => ['required', 'string', 'max:255'],
            'estado'          => ['required', 'string', 'max:255'],
            'complemento'     => ['nullable', 'string', 'max:255'],
            'telefone'        => ['required', 'string', 'max:20'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'cpf'             => ['required', 'string', 'max:14', Rule::unique(User::class)->ignore($id)],
            'foto'            => ['nullable', 'image', 'max:2048'],
        ];
    }
}
