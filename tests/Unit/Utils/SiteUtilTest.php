<?php

use PHPUnit\Framework\TestCase;
use App\Utils\SiteUtil;

class SiteUtilTest extends TestCase
{

    private $existing_class_names = [
        'KvartirantSite',
        'NeagentSite',
        'OnlinerSite',
    ];
    private $not_existing_class_names = [
        'NotExistingSite',
        'AnotherNotExistingSite',
    ];

    public function testGetSiteClassNames()
    {
        $actual_names = SiteUtil::getSiteClassNames();
        $this->assertEquals($this->existing_class_names, $actual_names);
    }

    public function testGetSitesNamespace()
    {
        $expected_namespace = "\\App\\Models\\Sites\\";

        $actual_namespace = SiteUtil::getSitesNamespace();

        $this->assertEquals($expected_namespace, $actual_namespace);
    }

    public function testGetSiteAlias()
    {
        foreach ($this->existing_class_names as $site_class_name) {
            $actual_alias = SiteUtil::getSiteAlias($site_class_name);
            $this->assertNotEquals($actual_alias, 'Еще сайт');
        }

        foreach ($this->not_existing_class_names as $site_class_name)
        {
            $actual_alias = SiteUtil::getSiteAlias($site_class_name);
            $this->assertEquals($actual_alias, 'Еще сайт');
        }
    }

}
