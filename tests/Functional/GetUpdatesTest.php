<?php

use PHPUnit\Framework\TestCase;
use App\Model\DB\SQLDB;
use App\Model\DB\DatabaseConfiguration;
use App\Factories\SiteFactory;
use App\Controllers\UpdatesController;
use App\Utils\SiteUtil;

class GetUpdatesTest extends TestCase
{

    /**
     *
     * Tested class object
     * @var SQLDB
     */
    private $sqldb;

    public function SetUp()
    {
        $db_config = new DatabaseConfiguration();
        $this->sqldb = new SQLDB($db_config);
    }

    public function testUpdates()
    {
//        $site_class_names = SiteUtil::getSiteClassNames();
        $site_class_names = [
            'OnlinerSite',
        ];
        foreach ($site_class_names as $site_class_name) {
            $site = SiteFactory::build($site_class_name);
            $actual_updates = UpdatesController::getSiteUpdate($site, $this->sqldb);

            $expected_updates = $this->sqldb->getLastUpdate($site_class_name);

            if (empty($actual_updates)) {
                $this->assertNotEquals($expected_updates, $actual_updates);
            } else {
                $this->assertEquals($expected_updates, $actual_updates);
            }
        }
    }

}
