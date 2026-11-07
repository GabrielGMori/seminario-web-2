<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'identificador',
        'estoque',
        'categoria',
        'categoria_id',
    ];

    protected function casts(): array
    {
        return [
            'estoque' => 'integer',
        ];
    }

    public function categoriaRelacionada(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }
}
