<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class AssetCategory extends Model
{
	protected $fillable = [
		'category_name',
        'company_id'
	];
}
