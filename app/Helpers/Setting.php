<?php

namespace App\Helpers;

use App\Models\Setting as SettingModel;
use Illuminate\Support\Facades\Cache;

class Setting
{
    public static function get($key, $default = null)
    {
        $settings = Cache::rememberForever('settings', function () {
            return SettingModel::pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }

    public static function clearCache()
    {
        Cache::forget('settings');
    }
}
