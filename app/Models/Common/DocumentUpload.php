<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_file_name',
        'type',
        'file_path',
        'file_size',
        'file_type',
        'documentable_id',
        'documentable_type',
        'company_id',
        'employee_id',
        'created_by',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }


    public function getIconAttribute() {
        $extension = strtolower($this->file_type);

        $rewrite_map = array(
            'jpeg' => 'jpg',
            'docx' => 'doc'
        );

        if(isset($rewrite_map[$extension])) {
            $extension = $rewrite_map[$extension];
        }

        if(file_exists(public_path('offers/images/svg/file-types/' . $extension . '.svg'))) {
            return '/offers/images/svg/file-types/' . $extension . '.svg';
        }

        return '/offers/images/svg/file-types/file.svg';
    }
}
