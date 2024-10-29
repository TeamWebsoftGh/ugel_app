<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceUser extends Model
{
    protected $connection = 'finance';
    protected $table = 'users';
    protected $fillable = ['password', 'enabled', 'name', 'email'];
}
