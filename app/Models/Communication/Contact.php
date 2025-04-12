<?php

namespace App\Models\Communication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;

    protected $appends =['fullname'];

    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'surname',
        'other_names',
        'email',
        'company',
        'phone_number',
        'date_of_birth',
        'contact_group_id'
    ];

    public function contactGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContactGroup::class)->withDefault(['name' => 'N/A']);
    }


    public function getFullNameAttribute()
    {
        return strtoupper(strtolower($this->first_name)).' ' . strtoupper(strtolower($this->other_names)) . ' ' . strtoupper(strtolower($this->surname));
    }
}
