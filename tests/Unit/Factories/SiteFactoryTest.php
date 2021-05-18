<?php

namespace App\Factories;

use PHPUnit\Framework\TestCase;
use App\Model\Site\KvartirantSite;

class FlatTest extends TestCase
{

    protected $site;

    public function testCanCreateSite()
    {
        $existing_site_class = 'KvartirantSite';
        $this->site = SiteFactory::build($existing_site_class);
        $this->assertInstanceOf(KvartirantSite::class, $this->site);
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);
        SiteFactory::build('not_existing_site_class');
    }
}
