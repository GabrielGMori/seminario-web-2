<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovimentacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'date'],
            'precoTotal' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'produtos' => ['required', 'array', 'min:1'],
            'produtos.*.produto_id' => [
                'required',
                'integer',
                Rule::exists('produtos', 'id')->whereNull('deleted_at'),
            ],
            'produtos.*.quantidade' => ['required', 'integer', 'min:1', 'max:4294967295'],
        ];
    }
}
