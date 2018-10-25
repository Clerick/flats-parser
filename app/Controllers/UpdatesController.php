<?php

namespace App\Controllers;

use App\Models\DB\DBInterface;
use App\Models\DB\SQLDB;

class UpdatesController
{

    /**
     *
     * @var DBInterface
     */
    private static $db;
    private static $updates = [];
    private static $site_folder_path;
    private static $files;

    private static function initialize()
    {
        self::$db = new SQLDB();
        self::$site_folder_path = realpath('./app/Models/Sites');
        self::$files = array_diff(scandir(self::$site_folder_path), array('.', '..'));
    }

    public static function getSiteUpdate(string $siteClass)
    {
        self::initialize();

        $siteClassFullName = "\\App\\Models\\Menus\\$siteClass";
        if (!class_exists($siteClassFullName)) {
            return null;
        }
        $site = new $siteClassFullName();

        $parsed_flats = $site->getFlats();
        $new_flats = self::$db->getNewFlats($parsed_flats, $site->getSiteName());
        self::$updates[$site->getSiteName()] = $new_flats;

        return self::$updates;
    }

    public static function getUpdates()
    {
        self::initialize();
        // Get updates for each site
        foreach (self::$files as $file) {
            try {
                $file_path = self::$site_folder_path . DIRECTORY_SEPARATOR . $file;
                if (file_exists($file_path)) {
                    require $file_path;
                } else {
                    echo 'не могу найти класс ' . $file_path;
                    die();
                }

                $flat_class = '\\App\Models\Sites\\' . str_replace('.php', '', $file);
                $site = strtolower($flat_class);
                $$site = new $flat_class();
                // TODO: close browser if error occuired
                $flats = $$site->getFlats();
                $new_flats = self::$db->getNewFlats($flats, $$site->getSiteName());
                self::$updates[$$site->getSiteName()] = $new_flats;
            } catch (\Exception $e) {
                // TODO: Log errors
                var_dump($e);
            }
        }

        return self::$updates;
    }
}
