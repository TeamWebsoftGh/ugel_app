<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class Trainer extends Model
{
	protected $fillable = [
		'first_name', 'last_name','contact_no','company_id','email','address',
		'expertise','status'
	];

	public function getFullNameAttribute() {
		return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
	}

}
