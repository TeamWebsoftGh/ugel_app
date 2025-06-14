<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationHelper {

    public static function getUnreadNotification()
    {
        $user =user();
        $rows = $user->unreadNotifications;
        $notifications = [];
        foreach ($rows->slice(0,12) as $k => $notification) {
            $notifications[] = [
                'id' => $notification->id,
                'created_at' => $notification->created_at,
                'created_at_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                'type' => $notification->type,
                'message' => $notification->data['message'],
                'read_at' => $notification->read_at,
                'url' => $notification->data['url']??"/",
                'icon' => $notification->data['icon']??'',
                'title' => $notification->data['title']??'',
                'user_id' => $user->id
            ];
        }

        // dd( $user->unreadNotifications, get_current_user());
        return $notifications;
    }

    public static function getCities($id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return City::where('province_id', $id)->get();
    }

    public static function getJobTitle()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return JobTitle::where('status', 1)->get();
    }

    public static function getJobType()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return JobType::where('status', 1)->get();
    }

    public static function getCategories()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Category::where('status', 1)->get();
    }

    public static function getCompanies()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Company::where('status', 1)->get();
    }

    public static function getIndustryTypes()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return IndustryType::where('status', 1)->get();
    }

    public static function getCompanySizes()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return CompanySize::where('status', 1)->get();
    }

    public static function getRoles()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Role::all()->skip(1);
    }

}
