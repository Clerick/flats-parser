<?php

namespace App\Models;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\Exception\WebDriverCurlException;

abstract class AbstractSite
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var Flat[]
     */
    protected $flats = [];

    /**
     *
     * @var string
     */
    protected $parse_url;

    /**
     *
     * @var \Exception
     */
    protected $error = null;

    /**
     *
     * @var type string
     */
    protected $host;

    /**
     *
     * @var RemoteWebDriver
     */
    protected $driver;

    public function __construct()
    {
        $this->host = $host = 'http://localhost:4400/wd/hub';
        $capability = DesiredCapabilities::chrome();
        $capability->setCapability("pageLoadStrategy", "none");
        $this->driver = RemoteWebDriver::create($host, $capability, 10000);
    }

    /**
     *
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getClassName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     *
     * @return Flat[]
     */
    public function getFlats()
    {
        $this->flats = [];

        $this->driver->manage()->timeouts()->implicitlyWait(10);
        $this->driver->get($this->parse_url);
        $this->waitPageLoad();

        $flat_rows = $this->getFlatsArray();
        $flat_rows_count = count($flat_rows);

        try {
            for ($i = 0; $i < $flat_rows_count; $i++) {
                if ($i > 0) {
                    $flat_rows = $this->getFlatsArray();
                }
                $flat = $this->getFlat($flat_rows[$i]);
                array_push($this->flats, $flat);
            }
        } catch (\Exception $ex) {
            // TODO: log exception
            var_dump($ex);
        } catch (\Error $e) {
            var_dump($e);
        } finally {
            $this->driver->close();
            return $this->flats;
        }
    }

    /**
     * Close webdriver
     */
    public function close()
    {
        $this->driver->close();
    }

    /**
     * Wait until important content is loads
     */
    abstract protected function waitPageLoad();

    /**
     *
     * @return RemoteWebElement[] A list of all elements, containing flat info
     */
    abstract protected function getFlatsArray();

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return Flat
     */
    abstract protected function getFlat(RemoteWebElement $flat_element): Flat;

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    abstract protected function getPrice(RemoteWebElement $flat_element): ?string;

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    abstract protected function getLink(RemoteWebElement $flat_element): ?string;

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    abstract protected function getTimestamp(RemoteWebElement $flat_element): ?string;

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    abstract protected function getDescription(RemoteWebElement $flat_element): ?string;

    /**
     *
     * @param RemoteWebElement $flat_element
     * @return string|null
     */
    abstract protected function getPhone(RemoteWebElement $flat_element): ?string;
}
