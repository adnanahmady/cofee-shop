<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function set(string $key, string $value): Setting
    {
        $setting = new Setting();
        $setting->setKey($key);
        $setting->setValue($value);
        $setting->save();

        return $setting;
    }

    public function get(string $key): Setting|null
    {
        return Setting::find($key);
    }
}
