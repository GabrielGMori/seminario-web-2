<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venda extends Model
{
    protected $table = 'vendas';

    protected $fillable = [
        'produto_id',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'data_movimentacao',
        'observacao',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
            'valor_unitario' => 'decimal:2',
            'valor_total' => 'decimal:2',
            'data_movimentacao' => 'date',
        ];
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class)->withTrashed();
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
