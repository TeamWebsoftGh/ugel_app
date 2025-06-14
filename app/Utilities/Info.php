<?php

namespace App\Utilities;

use Akaunting\Version\Version;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Traits\Settings;
use Composer\InstalledVersions;
use Illuminate\Support\Facades\DB;

class Info
{
    public static function all()
    {
        static $info = [];

        $basic = [
            'api_key' => static::getApiKey(),
            'ip' => "127.0.0.1",
        ];

        if (! empty($info) || is_cloud()) {
            return array_merge($info, $basic);
        }

        $info = array_merge(static::versions(), $basic, [
            'companies' => 1,
            'users' => 1,
            'invoices' => 0,
            'customers' => 1,
            'php_extensions' => static::phpExtensions(),
        ]);

        return $info;
    }

    public static function versions()
    {
        static $versions = [];

        if (! empty($versions)) {
            return $versions;
        }

        $versions = [
            'akaunting' => Version::short(),
            'laravel' => InstalledVersions::getPrettyVersion('laravel/framework'),
            'php' => static::phpVersion(),
            'mysql' => static::mysqlVersion(),
            'guzzle' => InstalledVersions::getPrettyVersion('guzzlehttp/guzzle'),
            'livewire' => InstalledVersions::getPrettyVersion('livewire/livewire'),
            'omnipay' => InstalledVersions::getPrettyVersion('league/omnipay'),
        ];

        return $versions;
    }

    public static function phpVersion()
    {
        return phpversion();
    }

    public static function phpExtensions()
    {
        return get_loaded_extensions();
    }

    public static function mysqlVersion()
    {
        static $version;

        if (empty($version) && (config('database.default') === 'mysql')) {
            $version = DB::selectOne('select version() as mversion')->mversion;
        }

        if (isset($version)) {
            return $version;
        }

        return 'N/A';
    }

    public static function ip()
    {
        return request()->header('CF_CONNECTING_IP')
                ? request()->header('CF_CONNECTING_IP')
                : request()->ip();
    }

    public static function getApiKey(): string
    {
        $setting = new class() { use Settings; };

        return $setting->getSettingValue('apps.api_key', '');
    }
}
