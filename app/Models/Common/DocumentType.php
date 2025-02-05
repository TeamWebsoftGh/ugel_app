<?php

namespace App\Models\Common;


use App\Abstracts\Model;

class DocumentType extends Model
{
	protected $fillable = [
		'document_type', 'company_id'
	];
}
