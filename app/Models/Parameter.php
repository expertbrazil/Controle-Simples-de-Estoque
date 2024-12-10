<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = [
        'key',
        'name',
        'value',
        'group',
        'type',
        'is_private',
        'description'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    public static function getValue($key, $default = null)
    {
        $parameter = self::where('key', $key)->first();
        return $parameter ? $parameter->value : $default;
    }

    public static function setValue($key, $value)
    {
        return self::where('key', $key)->update(['value' => $value]);
    }
}
