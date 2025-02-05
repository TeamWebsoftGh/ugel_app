<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class DocumentUpload extends Model
{
    protected $fillable = [
        'original_file_name',
        'type',
        'file_path',
        'file_size',
        'file_type',
        'documentable_id',
        'documentable_type',
        'company_id',
        'created_by',
    ];

    public function documentable()
    {
        return $this->morphTo();
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

        if(file_exists(public_path('assets/images/svg/file-types/' . $extension . '.svg'))) {
            return '/assets/images/svg/file-types/' . $extension . '.svg';
        }

        return '/assets/images/svg/file-types/file.svg';
    }
}
