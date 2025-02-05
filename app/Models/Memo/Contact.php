<?php

namespace App\Models\Memo;

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

    public function contact_group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContactGroup::class)->withDefault(['name' => 'N/A']);
    }

    public function getDateOfBirthAttribute($value)
    {
        if($value !== null & $value !== '')
        {
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }


    public function setDateOfBirthAttribute($value)
    {
        if($value !== null & $value !== '')
        {
            $this->attributes['date_of_birth'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['date_of_birth'] = null;
        }
    }

    public function getFullNameAttribute()
    {
        return strtoupper(strtolower($this->first_name)).' ' . strtoupper(strtolower($this->other_names)) . ' ' . strtoupper(strtolower($this->surname));
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format').' h:mA');
    }
}
