<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return $this->rulesFor();
    }

    public function rulesFor(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'estoque' => ['required', 'integer', 'min:0'],
            'preco_compra' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'preco_venda' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'categoria_id' => ['required', Rule::exists('categorias', 'id')->whereNull('deleted_at')],
        ];
    }
}
