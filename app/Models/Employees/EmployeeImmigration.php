<?php

namespace App\Models\Employees;

use App\Models\Common\DocumentType;
use App\Models\Common\DocumentUpload;
use App\Models\Settings\Country;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmployeeImmigration extends Model
{
    protected $fillable=[
        'document_number',
        'company_id',
        'employee_id',
        'eligible_review_date',
        'country_id',
        'issue_date',
        'expiry_date',
        'status',
    ];

	public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
		return $this->belongsTo(Country::class)->withDefault();
	}

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class)->withoutGlobalScope('exit_date');
    }

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function getDocumentFileAttribute()
    {
        return $this->documents()->first()?->file_path;
    }


//	public function getIssueDateAttribute($value)
//	{
//		return Carbon::parse($value)->format(env('Date_Format'));
//	}
}
