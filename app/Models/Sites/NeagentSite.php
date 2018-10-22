<?php namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\WebDriverBy;

class NeagentSite extends AbstractSite
{
    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "http://neagent.by/board/minsk/?catid=1&priceMin=50&priceMax=180&currency=2";
        $this->name = "neagent";
    }

    protected function getRoofRows()
    {
        return $this->driver->findElements(WebDriverBy::className('imd'));
    }

    protected function getPrice($roof_row)
    {
        return null;
    }

    protected function getLink($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::className('a_more'))[0]->getAttribute('href');
    }

    protected function getTimestamp($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::cssSelector('.md_head i'))[0]->getText();
    }

    protected function getDescription($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::className('imd_mess'))[0]->getText();
    }

    protected function getPhone($roof_row)
    {
        return null;
    }
}
