<?php

namespace App\Helpers;

use App\Models\LogType;
use App\Models\Permission;
use Illuminate\Support\Collection;

class LogTypeHelper {

    public static function getById(int $id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return LogType::find($id);
    }

    public static function getByName(string $name)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return LogType::where('slug', $name)->get()->first();
    }

    public static function getAll()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        if (user('admin')->hasRole('sys-admin'))
            return LogType::all();

        return LogType::all()->slice(1, 5);

    }

}
