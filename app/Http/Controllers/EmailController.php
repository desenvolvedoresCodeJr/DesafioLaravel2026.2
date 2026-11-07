<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MensagemAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function enviar(Request $request, User $usuario)
    {
        abort_if($usuario->is_admin, 403);

        $dados = $request->validate([
            'assunto'  => ['required', 'string', 'max:150'],
            'conteudo' => ['required', 'string', 'max:5000'],
        ]);

        try {
            Mail::to($usuario->email)->send(
                new MensagemAdmin($dados['assunto'], $dados['conteudo'], auth()->user()->name)
            );
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('usuarios.index')
                ->with('erro', 'Não foi possível enviar o e-mail. Verifique a configuração de envio.');
        }

        return redirect()->route('usuarios.index')->with('status', 'E-mail enviado para ' . $usuario->name . '!');
    }
}