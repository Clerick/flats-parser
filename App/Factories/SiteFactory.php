<?php

namespace App\Factories;

use App\Model\Site\AbstractSite;
use App\Utils\SiteUtil;

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

    private static function initialize()
    {
        self::$path_to_sites_folder = dirname(__DIR__) . "/Model/Site";
        self::$site_namespace = "\\App\\Model\\Site\\";
    }

    /**
     *
     * @param string $site_class_name
     * @return AbstractSite
     */
    public static function build(string $site_class_name): AbstractSite
    {
        self::initialize();
        $site_class_full_name = SiteUtil::getSitesNamespace() . $site_class_name;
        if (!class_exists($site_class_full_name)) {
            throw new \InvalidArgumentException("Cant find class $site_class_name");
        }
        return new $site_class_full_name();
    }

    /**
     *
     * @return AbstractSite[]
     */
    public static function getSitesArray()
    {
        self::initialize();
        $sites_files_array = glob(self::$path_to_sites_folder . "/*.php");
        $sites = [];

        foreach ($sites_files_array as $site_file) {
            $site_class_name = basename($site_file, '.php');
            $site = self::build($site_class_name);
            $sites[$site->getSiteName()] = $site;
        }

        return $sites;
    }

}
