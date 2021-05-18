<?php

namespace App\Utils;

class SiteUtil
{

    /**
     * @var string
     */
    private static $path_to_sites_folder;

    /**
     * @var string
     */
    private static $site_namespace;

    /**
     * @var array
     */
    private static $site_aliases = [
        'KvartirantSite' => 'kvartirant.by',
        'NeagentSite' => 'neagent.by',
        'OnlinerSite' => 'onliner.by',
        'KufarSite' => 'kufar.by'
    ];

    /**
     * @return void
     */
    private static function initialize(): void
    {
        self::$path_to_sites_folder = dirname(__DIR__) . "/Model/Site";
        self::$site_namespace = "\\App\\Model\\Site\\";
    }

    /**
     * @return array
     */
    public static function getSiteClassNames(): array
    {
        self::initialize();
        $sites_files_array = glob(self::$path_to_sites_folder . "/*.php");
        $site_names = [];

        foreach ($sites_files_array as $site_file) {
            if (!strpos($site_file, 'Abstract')) {
                $site_class_name = basename($site_file, '.php');
                $site_names[] = $site_class_name;
            }
        }

        return $site_names;
    }

    /**
     * @return string
     */
    public static function getSitesNamespace(): string
    {
        self::initialize();

        return self::$site_namespace;
    }

    /**
     * @param string $site_class_name
     * @return string
     */
    public static function getSiteAlias(string $site_class_name): string
    {
        if (array_key_exists($site_class_name, self::$site_aliases)) {
            return self::$site_aliases[$site_class_name];
        }
        return 'Еще сайт';
    }

}
