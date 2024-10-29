<?php

namespace App\Models\Organization;

use App\Models\Settings\Country;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $fillable = [
		'company_name',
        'company_type',
        'company_code',
        'trading_name',
        'registration_no',
        'contact_no',
        'email',
        'website',
        'tax_no',
        'country_id',
        'company_logo',
        'staff_id_prefix',
        'staff_id_format',
        'ssnit_no',
        'contact_person',
        'working_hours_per_month',
        'working_hours_per_day',
        'working_days_per_month',
        'probation_period',
        'address',
        'city',
        'state',
        'vision',
        'mission',
        'currency_id',
        'country_id',
        'zip',
	];

	public function companyHolidays()
    {
		return $this->hasMany(Holiday::class)
			->select('id','start_date','end_date','is_publish','company_id')
			->where('is_publish','=',1);
	}

	public function country()
    {
		return $this->hasOne(Country::class,'id','country_id')
            ->withDefault([
                'name' => 'Ghana',
                'id' => 1,
            ]);
	}

    public function makeCurrent($force = false)
    {
        if (!$force && $this->isCurrent()) {
            return $this;
        }

        static::forgetCurrent();

        // Bind to container
        app()->instance(static::class, $this);

//        // Load settings
//        setting()->setExtraColumns(['company_id' => $this->id]);
//        setting()->forgetAll();
//        setting()->load(true);

        return $this;
    }

    public function isCurrent()
    {
        return optional(static::getCurrent())->id === $this->id;
    }

    public function isNotCurrent()
    {
        return !$this->isCurrent();
    }

    public static function getCurrent()
    {
        if (!app()->has(static::class)) {
            return null;
        }

        return app(static::class);
    }

    public static function forgetCurrent()
    {
        $current = static::getCurrent();

        if (is_null($current)) {
            return null;
        }

        event(new CompanyForgettingCurrent($current));

        // Remove from container
        app()->forgetInstance(static::class);

        // Remove settings
        setting()->forgetAll();

        event(new CompanyForgotCurrent($current));

        return $current;
    }

    public static function hasCurrent()
    {
        return static::getCurrent() !== null;
    }

    /**
     * Scope to only include companies of a given enabled value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query, $value = 1)
    {
        return $query->where('is_active', $value);
    }
}
