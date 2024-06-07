<?php

namespace App\Models;

class CashTransaction extends BaseModel
{
    const TYPE_INITIAL_BALANCE = 0;
    const TYPE_ADJUSTMENT = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id', 'account_id', 'type', 'date', 'amount', 'description', 'notes'
    ];

    protected static $_types = [
        self::TYPE_INITIAL_BALANCE => 'Saldo Awal',
        self::TYPE_ADJUSTMENT => 'Penyesuaian Saldo',
    ];

    public static function types()
    {
        return self::$_types;
    }

    public function typeName()
    {
        return self::$_types[$this->type];
    }

    public function category()
    {
        return $this->belongsTo(CashTransactionCategory::class, 'category_id');
    }
}
