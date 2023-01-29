<?php

namespace App\Http\Requests;

class StoreProdutoRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:produtos,name',
            'price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image_url' => 'url|nullable',
        ];
    }
}
