<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceCompany extends Model
{
    protected $connection = 'finance';
    protected $table = 'companies';
    protected $fillable = ['password', 'enabled', 'name'];
}
