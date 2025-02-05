<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Priority extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'is_active'];
}
