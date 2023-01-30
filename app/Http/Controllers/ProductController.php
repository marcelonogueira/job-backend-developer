<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(SearchRequest $request)
    {
        $data = Product::search($request->toArray());

        return ProductResource::collection($data);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        if ($product) {
            return new ProductResource($product);
        }
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return ProductResource::make($product);
        } catch (\Exception $e) {
            return response()->noContent();
        }
    }
}
