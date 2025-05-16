<?php

namespace App\Models\Legal;

use App\Abstracts\Model;

class CourtCase extends Model
{
    protected $appends = ['display_name'];
    //
    protected $fillable = [
        'case_number',
        'title',
        'type',
        'category',
        'court_name',
        'always_cc',
        'status',
        'lawyer_id',
        'priority_id',
        'description',
        'note',
        'filed_at',
        'closed_at',
        'company_id',
        'created_by',
        'is_active',
    ];

    /**
     * Accessor for display_name.
     * Combines the title and case number.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->title} ({$this->case_number})";
    }
}
