<?php

namespace App\Models\Communication;

use App\Abstracts\Model;

class ContactGroup extends Model
{
    protected $fillable = ["name", "is_active", "description", "created_by", "company_id", "created_from"];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
