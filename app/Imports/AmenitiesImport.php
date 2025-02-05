<?php

namespace App\Imports;

use App\Abstracts\Import;
use App\Http\Requests\Property\AmenityRequest as Request;
use App\Models\Property\Amenity as Model;

class AmenitiesImport extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);
        return $row;
    }
}
