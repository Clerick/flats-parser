<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class OnlinerSite extends AbstractSite
{

    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "https://r.onliner.by/ak/?rent_type%5B%5D=1_room&price%5Bmin%5D=50&price%5Bmax%5D=180&currency=usd&only_owner=true#bounds%5Blb%5D%5Blat%5D=53.70036513128374&bounds%5Blb%5D%5Blong%5D=27.326431274414066&bounds%5Brt%5D%5Blat%5D=54.09483886777795&bounds%5Brt%5D%5Blong%5D=27.798156738281254";
        $this->name = "onliner";
    }

    /**
     *
     * @return RemoteWebElement[] A list of all elements, containing flat info
     */
    protected function getFlatsArray()
    {
        $this->driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('classified')));
        return $this->driver->findElements(WebDriverBy::className('classified'));
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

        // go to flat page to get description and price
        $flat_page_driver = $this->driver->get($flat->getLink());
        $flat_page = $flat_page_driver->findElement(WebDriverBy::tagName('body'));
        $flat->setDescription($this->getDescription($flat_page));
        $flat->setPhone($this->getPhone($flat_page));

        // return back to flats list page
        $this->driver->navigate()->back();
        $this->driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('classified')));

        return $flat;
    }

    protected function waitPageLoad()
    {
        $this->driver->wait(10, 1000)
            ->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('classified')));
    }


    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getPrice(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::cssSelector('.classified__price-value span'))->getText() . "$";
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
            return $flat_element->getAttribute('href');
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
            return $flat_element->findElement(WebDriverBy::cssSelector('.classified__time'))->getText();
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
            return $flat_element->findElement(WebDriverBy::cssSelector('.apartment-info__cell_66>.apartment-info__sub-line'))->getText();
        } catch (\Exception $e) {
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
        try {
            return $flat_element->findElement(WebDriverBy::cssSelector('ul.apartment-info__list_phones a'))->getText();
        } catch (\Exception $e) {
            // TODO: log exception
            return null;
        }
    }

}
