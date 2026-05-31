<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'settings_key';
    protected $returnType       = 'array';
    protected $allowedFields    = ['settings_key', 'settings_value', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Retrieve static configuration value by its key.
     */
    public static function getSetting(string $key, $default = null)
    {
        return setting('App.' . $key) ?? $default;
    }

    /**
     * Set static configuration value by its key.
     */
    public static function setSetting(string $key, $value): void
    {
        setting('App.' . $key, (string) $value);
    }
}
