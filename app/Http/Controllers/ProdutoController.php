<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    // Retorna todos os produtos
    public function index()
    {
        $produtos = Produto::all();
        return response()->json(['produtos' => $produtos], 200);
    }

    // Retorna um produto específico pelo ID
    public function show($id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return response()->json(['produto' => $produto], 200);
    }

    // Cria um novo produto
    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
            'preco' => 'required|numeric|min:0',
            
        ]);

        $produto = Produto::create([
            'nome' => $request->input('nome'),
            'preco' => $request->input('preco'),
            
        ]);

        return response()->json(['message' => 'Produto criado com sucesso', 'produto' => $produto], 201);
    }

    
    public function update(Request $request, $id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $this->validate($request, [
            'nome' => 'required',
            'preco' => 'required|numeric|min:0',
           
        ]);

        $produto->nome = $request->input('nome');
        $produto->preco = $request->input('preco');
        
        $produto->save();

        return response()->json(['message' => 'Produto atualizado com sucesso', 'produto' => $produto], 200);
    }

    
    public function destroy($id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $produto->delete();

        return response()->json(['message' => 'Produto removido com sucesso'], 200);
    }
}
