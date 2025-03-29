<?php

namespace App\Models\Client;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Settings\Country;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $appends =['full_name'];

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'client_number',
        'other_names',
        'email',
        'email_cc',
        'phone_number',
        'date_of_birth',
        'gender',
        'approved_at',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'status',
        'comment',
        'terms_and_condition',
        'account_officer_id',
        'referral_code',
        'country_id',
        'business_name',
        'business_telephone',
        'physical_address',
        'postal_address',
        'website',
        'certificate_of_incorporation',
        'number_of_employees',
        'date_of_incorporation',
        'tin_number',
        'type_of_business',
        'business_email',
        'client_type_id',
        'created_from',
        'created_by',
        'import_id',
        'company_id',
    ];

    // Relationships
    public function clientType()
    {
        return $this->belongsTo(ClientType::class)->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getFullNameAttribute()
    {
        if(isset($this->business_name))
        {
            return ucwords(strtolower($this->business_name));
        }
        return ucwords(strtolower($this->title)).' ' . ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name));
    }

    public function getNameAttribute()
    {
        if(isset($this->business_name))
        {
            return ucwords(strtolower($this->business_name));
        }
        return ucwords(strtolower($this->title)).' ' . ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name)). ' (' . ucwords(strtolower($this->customer_number??$this->phone_number)).')';
    }


    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
