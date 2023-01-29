<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

abstract class AbstractRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Erros de validação',
            'data'      => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
