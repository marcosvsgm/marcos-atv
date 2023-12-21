<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    // Retorna todas as categorias
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json(['categorias' => $categorias], 200);
    }

    // Retorna uma categoria específica pelo ID
    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        return response()->json(['categoria' => $categoria], 200);
    }

    // Cria uma nova categoria
    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|unique:categorias,nome'
            // Adicione outras regras de validação conforme necessário
        ]);

        $categoria = Categoria::create([
            'nome' => $request->input('nome')
            // Adicione outros campos conforme necessário
        ]);

        return response()->json(['message' => 'Categoria criada com sucesso', 'categoria' => $categoria], 201);
    }

    // Atualiza uma categoria existente pelo ID
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $this->validate($request, [
            'nome' => 'required|unique:categorias,nome,'.$id
            // Adicione outras regras de validação conforme necessário
        ]);

        $categoria->nome = $request->input('nome');
        // Atualize outros campos conforme necessário
        $categoria->save();

        return response()->json(['message' => 'Categoria atualizada com sucesso', 'categoria' => $categoria], 200);
    }

    // Remove uma categoria pelo ID
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria removida com sucesso'], 200);
    }
}

