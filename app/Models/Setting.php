<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $settings = self::getCachedAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings from cache.
     *
     * @return array
     */
    public static function getCachedAll()
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('settings_all', function () {
            return self::pluck('value', 'key')->toArray();
        });
    }
}
