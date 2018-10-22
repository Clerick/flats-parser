<?php namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\WebDriverBy;
use \Exception;

class KvartirantSite extends AbstractSite
{
    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "http://www.kvartirant.by/ads/flats/type/rent/?tx_uedbadsboard_pi1%5Bsearch%5D%5Bq%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdistrict%5D=0&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bfrom%5D=50&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bto%5D=180&tx_uedbadsboard_pi1%5Bsearch%5D%5Bcurrency%5D=840&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdate%5D=86400&tx_uedbadsboard_pi1%5Bsearch%5D%5Bagency_id%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bowner%5D=on";
        $this->name      = "kvartirant";
    }

    protected function getRoofRows()
    {
        return $this->driver->findElements(WebDriverBy::xpath("//table[@class='ads_list_table']/tbody/tr[td[@class='adtxt']]"));
    }

    protected function getPrice($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::cssSelector('span.price-box'))[0]->getText();
    }

    protected function getLink($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::cssSelector('.title a'))[0]->getAttribute('href');
    }

    protected function getTimestamp($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::className('date'))[0]->getText();
    }

    protected function getDescription($roof_row)
    {
        return $roof_row->findElements(WebDriverBy::xpath('.//td/div/p[3]'))[0]->getText();
    }

    protected function getPhone($roof_row)
    {
        return null;
    }
}
