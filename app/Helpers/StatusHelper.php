<?php

namespace App\Helpers;

use App\Models\Common\Status;
use Illuminate\Support\Collection;

class StatusHelper {

    public static function getById(int $id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return BookingStatus::find($id);
    }

    public static function getByName(string $name)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return optional(Status::where('name', $name)->get()->first())->id;
    }

    public static function getAll()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        if (user('tasks')->hasRole('super-tasks'))
            return BookingStatus::all();

        return BookingStatus::all()->slice(1, 5);

    }

    public static function getAllTaskStatuses()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return Status::where(['module' => 'task', 'is_active' => 1])->get();

    }

}
