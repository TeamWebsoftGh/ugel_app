<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class AwardType extends Model
{
	protected $fillable = [
         'award_name', 'company_id'
	];
}
