<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class Priority extends Model
{
    protected $fillable = ['name', 'company_id', 'is_active'];
}
