<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return $this->rulesFor($this->route('categoria'));
    }

    public function rulesFor(int|string|null $categoriaId = null): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nome')->ignore($categoriaId),
            ],
            'descricao' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
