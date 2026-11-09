<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'estoque',
        'preco_compra',
        'preco_venda',
        'categoria',
        'categoria_id',
    ];

    protected function casts(): array
    {
        return [
            'estoque' => 'integer',
            'preco_compra' => 'decimal:2',
            'preco_venda' => 'decimal:2',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id')->withTrashed();
    }

    public function vendas(): BelongsToMany
    {
        return $this->belongsToMany(Venda::class, 'venda_produto')->withPivot('quantidade');
    }

    public function compras(): BelongsToMany
    {
        return $this->belongsToMany(Compra::class, 'compra_produto')->withPivot('quantidade');
    }
}
