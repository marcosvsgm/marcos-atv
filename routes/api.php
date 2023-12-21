<?php

// routes/api.php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;

Route::resource('produtos', ProdutoController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('pedidos', PedidoController::class);

Route::post('pedidos/realizar', [PedidoController::class, 'realizarPedido']);
Route::post('pedidos/calcular-frete', [PedidoController::class, 'calcularFrete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
