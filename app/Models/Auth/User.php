<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Client\Client;
use App\Models\Common\Company;
use App\Traits\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Users, HasRoles;

    protected $appends = ['full_name', 'role_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'gender',
        'last_name',
        'password',
        'company_id',
        'phone_number',
        'photo',
        'is_active',
        'client_id',
        'last_login_ip',
        'last_login_date',
        'ask_password_reset',
        'last_password_reset',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public static function boot()
//    {
//        parent::boot();
//
//        static::retrieved(function ($model) {
//            $model->setCompanyIds();
//        });
//
//        static::saving(function ($model) {
//            $model->unsetCompanyIds();
//        });
//    }

    public function getRoleIdAttribute()
    {
        return $this->roles()->first()->id??"";
    }

    public function getRoleNameAttribute()
    {
        return implode(', ', $this->roles()->get()->pluck('display_name', 'id')->toArray());
    }

    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->title)).' ' . ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name));
    }

    public function getLastLoginDateAttribute($value)
    {
        if ($value)
        {
            return Carbon::parse($value)->format(env('Date_Format').'--H:i');
        }
        else {
            return null;
        }
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Attach company_ids attribute to model.
     *
     * @return void
     */
    public function setCompanyIds()
    {
//        $company_ids = $this->withoutEvents(function () {
//            return $this->companies->pluck('id')->toArray();
//        });

        $this->setAttribute('company_ids', [1]);
    }

    /**
     * Detach company_ids attribute from model.
     *
     * @return void
     */
    public function unsetCompanyIds()
    {
        $this->offsetUnset('company_ids');
    }

    public function getUserImageAttribute()
    {
        return $this->attributes['photo'] ??"assets/images/user.png";
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
