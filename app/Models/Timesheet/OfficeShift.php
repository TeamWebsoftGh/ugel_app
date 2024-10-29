<?php

namespace App\Models\Timesheet;

use App\Abstracts\Model;

class OfficeShift extends Model
{
    protected $fillable=[
        'shift_name',
        'company_id',
        'created_by',
        'default_shift',
        'monday_in',
        'monday_out',
        'tuesday_in',
        'tuesday_out',
        'wednesday_in',
        'wednesday_out',
        'thursday_in',
        'thursday_out',
        'friday_in',
        'friday_out',
        'saturday_in',
        'saturday_out',
        'sunday_in',
        'sunday_out'
    ];

    protected $appends =['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function getMondayAttribute()
    {
        return $this->monday_in ? "{$this->monday_in} to {$this->monday_out}" : "N/A";
    }

    public function getTuesdayAttribute()
    {
        return $this->tuesday_in ? "{$this->tuesday_in} to {$this->tuesday_out}" : "N/A";
    }

    public function getWednesdayAttribute()
    {
        return $this->wednesday_in ? "{$this->wednesday_in} to {$this->wednesday_out}" : "N/A";
    }

    public function getThursdayAttribute()
    {
        return $this->thursday_in ? "{$this->thursday_in} to {$this->thursday_out}" : "N/A";
    }

    public function getFridayAttribute()
    {
        return $this->friday_in ? "{$this->friday_in} to {$this->friday_out}" : "N/A";
    }

    public function getSaturdayAttribute()
    {
        return $this->saturday_in ? "{$this->saturday_in} to {$this->saturday_out}" : "N/A";
    }

    public function getSundayAttribute()
    {
        return $this->sunday_in ? "{$this->sunday_in} to {$this->sunday_out}" : "N/A";
    }

}
