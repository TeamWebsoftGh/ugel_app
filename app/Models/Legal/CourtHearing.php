<?php

namespace App\Models\Legal;

use Illuminate\Database\Eloquent\Model;

class CourtHearing extends Model
{
    //
    protected $fillable = [
        'court_case_id',
        'venue',
        'judge',
        'outcome',
        'notes',
        'date',
        'time',
        'company_id',
        'created_by',
        'is_active',
    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class);
    }
}
