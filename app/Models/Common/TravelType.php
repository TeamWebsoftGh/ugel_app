<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class TravelType extends Model
{
	protected $fillable = [
		'arrangement_type', 'company_id'
	];
}
