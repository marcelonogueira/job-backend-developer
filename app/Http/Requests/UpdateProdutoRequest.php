<?php

namespace App\Http\Requests;

class UpdateProdutoRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:produtos,name,' . $this->produto->id,
            'price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image_url' => 'url|nullable',
        ];
    }
}
