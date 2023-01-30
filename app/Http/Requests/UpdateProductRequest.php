<?php

namespace App\Http\Requests;

class UpdateProductRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'unique:products,name,' . $this->product->id],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
            'category' => ['required', 'max:255'],
            'image_url' => ['url', 'nullable'],
        ];
    }
}
