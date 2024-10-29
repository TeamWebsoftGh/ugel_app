<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function logAction()
    {
        return $this->belongsTo(LogAction::class)->withDefault();
    }
}
