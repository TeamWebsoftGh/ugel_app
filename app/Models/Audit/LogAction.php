<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAction extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->log_type_id = $model->log_type_id??3;
            $model->name = $model->name??ucwords(str_replace('-', ' ', $model->slug));
        });
    }
    protected $fillable = ['slug'];

    public function logType()
    {
        return $this->belongsTo(LogType::class)->withDefault();
    }
}
