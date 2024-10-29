<?php

namespace App\Models\CustomerService;

use App\Models\Common\DocumentUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $table ="customer_enquiries";

    protected $fillable = [
        'name',
        'client_id',
        'email',
        'phone_number',
        'subject',
        'message',
        'client_ip',
        'form_id',
        'client_agent'
    ];

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

}
