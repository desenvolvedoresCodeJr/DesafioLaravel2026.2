<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GraficoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProdutoController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/produtos/{produto}', [ProdutoController::class, 'show'])
    ->middleware(['auth'])
    ->name('produtos.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/gerenciar-produtos', [ProdutoController::class, 'gerenciar'])->name('produtos.gerenciar');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::post('/carrinho/adicionar/{produto}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
    Route::patch('/carrinho/{item}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
    Route::delete('/carrinho/{item}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
    Route::delete('/carrinho', [CarrinhoController::class, 'esvaziar'])->name('carrinho.esvaziar');

    Route::post('/checkout', [CheckoutController::class, 'iniciar'])->name('checkout.iniciar');
    Route::get('/checkout/retorno', [CheckoutController::class, 'retorno'])->name('checkout.retorno');

    Route::get('/compras', [HistoricoController::class, 'compras'])->name('compras.index');
    Route::get('/compras/relatorio', [HistoricoController::class, 'comprasPdf'])->name('compras.pdf');
    Route::get('/compras/{item}/pdf', [HistoricoController::class, 'compraPdf'])->name('compras.item.pdf');

    Route::get('/vendas', [HistoricoController::class, 'vendas'])->name('vendas.index');
    Route::get('/vendas/relatorio', [HistoricoController::class, 'vendasPdf'])->name('vendas.pdf');
    Route::get('/vendas/xlsx', [HistoricoController::class, 'vendasXlsx'])->name('vendas.xlsx');
    Route::get('/vendas/{item}/pdf', [HistoricoController::class, 'vendaPdf'])->name('vendas.item.pdf');

    Route::get('/grafico/produtos', [GraficoController::class, 'produtos'])->name('grafico.produtos');
    Route::get('/grafico/vendas', [GraficoController::class, 'vendas'])->name('grafico.vendas');
});

Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    Route::get('/administradores', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/administradores', [AdminController::class, 'store'])->name('admins.store');
    Route::put('/administradores/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/administradores/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

    Route::post('/usuarios/{usuario}/email', [EmailController::class, 'enviar'])->name('usuarios.email');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
