<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class TerminationType extends Model
{
	protected $fillable = [
		'termination_title',
        'description',
        'company_id'
	];


}
