<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

class KvartirantSite extends AbstractSite
{

    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "http://www.kvartirant.by/ads/flats/type/rent/?tx_uedbadsboard_pi1%5Bsearch%5D%5Bq%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdistrict%5D=0&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bfrom%5D=50&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bto%5D=180&tx_uedbadsboard_pi1%5Bsearch%5D%5Bcurrency%5D=840&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdate%5D=86400&tx_uedbadsboard_pi1%5Bsearch%5D%5Bagency_id%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bowner%5D=on";
        $this->name = "kvartirant";
    }

    /**
     *
     * @return RemoteWebElement[] A list of all elements, containing flat info
     */
    protected function getFlatsArray()
    {
        return $this->driver->findElements(WebDriverBy::xpath("//table[@class='ads_list_table']/tbody/tr[td[@class='adtxt']]"));
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return Flat
     */
    protected function getFlat(RemoteWebElement $flat_element): Flat
    {
        $flat = new Flat();

        $flat->setPrice($this->getPrice($flat_element));
        $flat->setLink($this->getLink($flat_element));
        $flat->setTimestamp($this->getTimestamp($flat_element));
        $flat->setDescription($this->getDescription($flat_element));
        $flat->setPhone($this->getPhone($flat_element));

        return $flat;
    }

    protected function waitPageLoad()
    {
        $this->driver->wait(60, 1000)
            ->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//table[@class='ads_list_table']/tbody/tr[td[@class='adtxt']]")));
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getPrice(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::cssSelector('span.price-box'))->getText();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getLink(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::cssSelector('.title a'))->getAttribute('href');
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getTimestamp(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::className('date'))->getText();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getDescription(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::xpath('.//td/div/p[3]'))->getText();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getPhone(RemoteWebElement $flat_element): ?string
    {
        return null;
    }

}
