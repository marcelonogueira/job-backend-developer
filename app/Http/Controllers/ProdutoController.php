<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = [
            'id' => $request->id,
            'name_category' => $request->name_category,
            'category' => $request->category,
            'with_image' => $request->with_image,
        ];

        $dados = Produto::search($filtros, $request->limite);

        return ProdutoResource::collection($dados);
    }

    public function store(StoreProdutoRequest $request)
    {
        $produto = Produto::create($request->all());

        if ($produto) {
            return new ProdutoResource($produto);
        }
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(UpdateProdutoRequest $request, Produto $produto)
    {
        $produto->update($request->all());

        return new ProdutoResource($produto);
    }

    public function destroy(Produto $produto)
    {
        try {
            $produto->delete();

            return ProdutoResource::make($produto);
        } catch (\Exception $e) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }
    }
}
