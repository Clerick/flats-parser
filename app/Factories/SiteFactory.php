<?php

namespace App\Factories;

use App\Models\AbstractSite;

class SiteFactory
{

    /**
     *
     * @var string
     */
    private static $site_namespace;

    /**
     *
     * @var string
     */
    private static $path_to_sites_folder;

    private function initialize()
    {
        self::$path_to_sites_folder = dirname(__DIR__) . "/Models/Sites";
        self::$site_namespace = "\\App\\Models\\Sites\\";
    }

    /**
     *
     * @param string $site_class_name
     * @return AbstractSite
     */
    public static function build(string $site_class_name): AbstractSite
    {
        self::initialize();
        $site_class_full_name = self::$site_namespace . $site_class_name;
        if (!class_exists($site_class_full_name)) {
            throw new \Exception("Cant find clas $site_class_name");
        }
        return new $site_class_full_name();
    }

    /**
     *
     * @return AbstractSite[]
     */
    public static function getSitesArray()
    {
        $sites_files_array = glob(self::$path_to_sites_folder . "/*.php");
        $sites = [];

        foreach ($sites_files_array as $site_file) {
            $site_class_name = basename($site_file, '.php');
            $site = self::build($site_class_name);
            $sites[$site->getSiteName()] = $site;
        }

        return $sites;
    }

    /**
     *
     * @return array
     */
    public static function getSiteClassNames(): array
    {
        self::initialize();
        $sites_files_array = glob(self::$path_to_sites_folder . "/*.php");
        $site_names = [];

        foreach ($sites_files_array as $site_file) {
            $site_class_name = basename($site_file, '.php');
            $site_names[] = $site_class_name;
        }

        return $site_names;
    }

}
