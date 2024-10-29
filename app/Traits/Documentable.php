<?php

namespace App\Traits;

use App\Models\Common\DocumentUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait Documentable
{
    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }
}
