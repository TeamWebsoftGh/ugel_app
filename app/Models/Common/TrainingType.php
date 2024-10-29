<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class TrainingType extends Model
{
	protected $fillable = [
		'type','company_id','status'
	];
}
