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
        'name', 'type', 'bank', 'number', 'balance', 'active', 'notes'
    ];

    public function idFormatted()
    {
        return 'CA' . str_pad($this->id, 2, '0', STR_PAD_LEFT);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
