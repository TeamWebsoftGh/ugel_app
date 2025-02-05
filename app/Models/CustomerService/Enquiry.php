<?php

namespace App\Models\CustomerService;

use App\Abstracts\Model;
use App\Models\Common\DocumentUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enquiry extends Model
{
    use HasFactory;

    protected $table ="customer_enquiries";

    protected $appends = ['full_name'];


    protected $fillable = [
        'first_name',
        'last_name',
        'client_id',
        'email',
        'phone_number',
        'subject',
        'message',
        'client_ip',
        'status',
        'form_id',
        'client_agent'
    ];

    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name));
    }

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

}
