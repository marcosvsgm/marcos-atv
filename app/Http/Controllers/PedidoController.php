<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido; // Importe o modelo do Pedido
use App\Models\Produto; // Importe o modelo do Produto
use BetoSouza\PhpCorreios\Frete;

class PedidoController extends Controller
{
    public function realizarPedido(Request $request)
{
    // Verificar se os produtos estão presentes na requisição
    if (!$request->has('produtos') || empty($request->produtos)) {
        return response()->json(['message' => 'Nenhum produto encontrado no pedido'], 400);
    }

    // Lógica para criar um novo pedido
    $novoPedido = Pedido::create([
        'codigo_pedido' => uniqid(), // Gerar um código de pedido único
        'total' => 0, // O total será calculado com base nos produtos
        'status' => 'pendente',
    ]);

    // Adicionar produtos ao pedido
    foreach ($request->produtos as $produto) {
        $produtoDB = Produto::find($produto['produto_id']);
        
        // Verificar se o produto existe e está em estoque
        if (!$produtoDB || $produtoDB->quantidade_estoque < $produto['quantidade']) {
            // Lógica para lidar com produtos não disponíveis em estoque
            return response()->json(['message' => 'Produto não disponível em estoque'], 400);
        }

        // Calcular subtotal e atualizar estoque
        $subtotal = $produtoDB->preco * $produto['quantidade'];
        $novoPedido->produtos()->attach($produto['produto_id'], [
            'quantidade' => $produto['quantidade'],
            'preco_unitario' => $produtoDB->preco,
            'subtotal' => $subtotal,
        ]);

        // Atualizar estoque do produto
        $produtoDB->quantidade_estoque -= $produto['quantidade'];
        $produtoDB->save();

        // Adicionar ao total do pedido
        $novoPedido->total += $subtotal;
    }

    // Salvar o total do pedido após calcular todos os subtotais
    $novoPedido->save();

    return response()->json(['message' => 'Pedido realizado com sucesso', 'pedido' => $novoPedido], 201);
     
    
    
}

}
