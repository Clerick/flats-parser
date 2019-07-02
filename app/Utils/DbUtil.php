<?php
namespace App\Utils;

use App\Utils\SiteUtil;
use App\Models\DB\SQLDB;
use App\Models\DB\DatabaseConfiguration;

class DbUtil
{
    public static function createTablesForAllSites()
    {
        $siteNames = SiteUtil::getSiteClassNames();
        $dbConfig = new DatabaseConfiguration();
        $dbProvider = new SQLDB($dbConfig);

        foreach ($siteNames as $siteName) {
            echo 'Create table for ' . $siteName . "\r\n";
            $dbProvider->createTable($siteName);
        }

        echo 'done';
    }
}
