<?php

namespace App\Models\Memo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGroup extends Model
{
    use HasFactory;

    protected $fillable = ["name", "is_active", "description"];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
