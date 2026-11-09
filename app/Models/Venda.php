<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use SoftDeletes;

    protected $table = 'vendas';

    protected $fillable = [
        'data',
        'preco',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'venda_produto')->withPivot('quantidade');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
