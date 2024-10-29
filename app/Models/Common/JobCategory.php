<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class JobCategory extends Model
{
    //
	protected $fillable=['job_category', 'company_id'];

	public $timestamps = false;


}
