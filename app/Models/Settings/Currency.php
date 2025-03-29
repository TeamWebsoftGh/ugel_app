<?php

namespace App\Models\Settings;


use App\Abstracts\Model;

class Currency extends Model
{
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency',
        'code',
        'exchange_rate',
        'is_active',
        'precision',
        'symbol',
        'symbol_first',
        'decimal_separator',
        'thousands_separator',
        'is_default',
        'standard_exchange_rate',
    ];

    public function getDisplayNameAttribute()
    {
        return $this->symbol . ' (' . $this->currency . ')';
    }

    /**
     * getPrefix.
     *
     * @return string
     */
    public function getPrefixattribute()
    {
        if (!$this->symbol_first) {
            return '';
        }

        return $this->symbol;
    }

    /**
     * getSuffix.
     *
     * @return string
     */
    public function getSuffixattribute()
    {
        if ($this->symbol_first) {
            return '';
        }

        return ' ' . $this->symbol;
    }

    /**
     * getCurrencies.
     *
     * @return array
     */
    public static function getCurrencies()
    {
        return Currency::all()->where('is_active', '==', 1);
    }
}
