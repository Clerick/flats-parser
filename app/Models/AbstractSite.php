<?php namespace App\Models;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

abstract class AbstractSite
{
    protected $name;
    protected $flats = [];
    protected $parse_url;
    protected $error = null;
    protected $host;
    protected $driver;

    public function __construct()
    {
        $this->host   = $host   = 'http://localhost:4400/wd/hub';
        $this->driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome(), 10000);
    }

    public function getSiteName()
    {
        return $this->name;
    }

    public function getFlats()
    {
        $this->flats = [];

        $this->driver->manage()->timeouts()->implicitlyWait(10);
        $this->driver->get($this->parse_url);
        $roof_rows = $this->getRoofRows();

        foreach ($roof_rows as $roof_row) {
            $flat = new Flat();
            $flat->price = $this->getPrice($roof_row);
            $flat->link = $this->getLink($roof_row);
            $flat->timestamp = $this->getTimestamp($roof_row);
            $flat->description = $this->getDescription($roof_row);
            $flat->phone = $this->getPhone($roof_row);

            array_push($this->flats, $flat);
        }

        $this->driver->close();

        return $this->flats;
    }

    abstract protected function getRoofRows();
    abstract protected function getPrice($roof_row);
    abstract protected function getLink($roof_row);
    abstract protected function getTimestamp($roof_row);
    abstract protected function getDescription($roof_row);
    abstract protected function getPhone($roof_row);
}
