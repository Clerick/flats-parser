<?php namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;

class OnlinerSite extends AbstractSite
{
    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "https://r.onliner.by/ak/?rent_type%5B%5D=1_room&price%5Bmin%5D=50&price%5Bmax%5D=180&currency=usd&only_owner=true#bounds%5Blb%5D%5Blat%5D=53.70036513128374&bounds%5Blb%5D%5Blong%5D=27.326431274414066&bounds%5Brt%5D%5Blat%5D=54.09483886777795&bounds%5Brt%5D%5Blong%5D=27.798156738281254";
        $this->name = "onliner";
    }

    protected function getRoofRows()
    {
        return $this->driver->findElements(WebDriverBy::className('classified'));
    }

    protected function getPrice($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::cssSelector('.classified__price-value span'))[0]->getText() . "$";
    }

    protected function getLink($roof_row)
    {
        return $roof_row->getAttribute('href');
    }

    protected function getTimestamp($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::className('classified__time'))[0]->getText();
    }

    protected function getDescription($roof_row)
    {
        return null;
    }

    protected function getPhone($roof_row)
    {
        return null;
    }
}
