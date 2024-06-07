<?php

namespace App\Models;

class CashTransaction extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id', 'date', 'amount', 'description', 'notes'
    ];

    public function category()
    {
        return $this->belongsTo(CashTransactionCategory::class, 'category_id');
    }
}
