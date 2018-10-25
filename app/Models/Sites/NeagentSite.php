<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

class NeagentSite extends AbstractSite
{

    public function __construct()
    {
        parent::__construct();
        $this->parse_url = "http://neagent.by/board/minsk/?catid=1&priceMin=50&priceMax=180&currency=2";
        $this->name = "neagent";
    }

    /**
     *
     * @return RemoteWebElement[] A list of all elements, containing flat info
     */
    protected function getFlatsArray()
    {
        return $this->driver->findElements(WebDriverBy::className('imd'));
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return Flat
     */
    protected function getFlat(RemoteWebElement $flat_element): Flat
    {
        $flat = new Flat();
        $flat->price = $this->getPrice($flat_element);
        $flat->link = $this->getLink($flat_element);
        $flat->timestamp = $this->getTimestamp($flat_element);
        $flat->description = $this->getDescription($flat_element);


        // Go to flat page to get phone
        $flat_page_driver = $this->driver->get($flat->link);
        $flat_page = $flat_page_driver->findElement(WebDriverBy::tagName('body'));
        $flat->phone = $this->getPhone($flat_page);

        // return back to flats list page
        $this->driver->navigate()->back();
        $this->driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('imd')));

        return $flat;
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getPrice(RemoteWebElement $flat_element): ?string
    {
        return null;
    }

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    protected function getLink(RemoteWebElement $flat_element): ?string
    {
        try {
            return $flat_element->findElement(WebDriverBy::className('a_more'))->getAttribute('href');
        } catch (Exception $ex) {
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
            return $flat_element->findElement(WebDriverBy::cssSelector('.md_head i'))->getText();
        } catch (Exception $ex) {
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
            return $flat_element->findElement(WebDriverBy::className('imd_mess'))->getText();
        } catch (Exception $ex) {
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
            $flat_element->findElement(WebDriverBy::className('phone_show'))->click();
            $this->driver->wait(5, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('cphone')));
            $phone = $this->driver->findElement(WebDriverBy::className('cphone'))->getText();
            return $phone;
        } catch (Exception $ex) {
            // TODO:log exception
            return null;
        }
    }

}
