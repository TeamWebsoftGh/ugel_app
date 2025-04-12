<?php

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Model;

class PopupBuilder extends Model
{
    protected $fillable = [
        'name',
        'type',
        'title',
        'only_image',
        'cover_image',
        'offer_time_end',
        'button_text',
        'button_link',
        'btn_status',
        'description',
        'lang',
        'start_date',
        'end_date',
        'company_id',
        'department_id',
        'subsidiary_id',
    ];

    public function getDurationAttribute()
    {
        if($this->start_date != $this->end_date){
            return date('d-m-Y', strtotime($this->start_date)).' to ' . date('d-m-Y', strtotime($this->end_date));
        }
        return date('d-m-Y', strtotime($this->start_date));
    }
}
