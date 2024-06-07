<?php

namespace App\Models;

class CashAccount extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'type', 'bank', 'account_number', 'balance'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
