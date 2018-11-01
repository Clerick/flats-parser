<?php

namespace App\Controllers;

use PHPUnit\Framework\TestCase;
use App\Models\DB\DBInterface;
use App\Models\AbstractSite;
use App\Models\Flat;

class UpdatesControllerTest extends TestCase
{

    private function createSiteMock(string $site_name)
    {
        $site_mock = $this->createMock(AbstractSite::class);
        $flat_mock = $this->createFlatMock();
        $site_mock->method('getFlats')->willReturn([$flat_mock]);
        $site_mock->method('getSiteName')->willReturn($site_name);

        return $site_mock;
    }

    private function createFlatMock()
    {
        $flat_mock = $this->createMock(Flat::class);
        return $flat_mock;
    }

    private function createDbMock()
    {
        $db_mock = $this->createMock(DBInterface::class);
        $flat_mock = $this->createFlatMock();
        $db_mock->method('getNewFlats')->willReturn([$flat_mock]);

        return $db_mock;
    }

    public function testGetSiteUpdate()
    {
        $site = $this->createSiteMock('test_site');
        $db = $this->createDbMock();

        $actual_flats = UpdatesController::getSiteUpdate($site, $db);

        foreach ($actual_flats as $actual_flat) {
            $this->assertInstanceOf(Flat::class, $actual_flat);
        }
    }

}
