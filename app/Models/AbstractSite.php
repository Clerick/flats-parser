<?php

namespace App\Models;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

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
     * @var Goutte\Client
     */
    protected $client;

    public function __construct()
    {
        $this->parse_url = getenv(strtoupper($this->name) . '_PARSE_URL');
        $this->client = new Client();
        $guzzleClient = new \GuzzleHttp\Client([
            'curl' => [
                CURLOPT_TIMEOUT => 60,
            ]
        ]);
        $this->client->setClient($guzzleClient);
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

        $result = $this->getFlatsArray();

        try {
            switch (gettype($result)) {
                case 'array':
                    foreach ($result as $apartment) {
                        $flat = $this->getFlat($apartment);
                        array_push($this->flats, $flat);
                    }
                    break;

                case 'object':
                    $result->each(function (Crawler $node) {
                        $flat = $this->getFlat($node);
                        array_push($this->flats, $flat);
                    });
                    break;
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . '<br>';
            echo $e->getTraceAsString();
            // TODO: log exception
        } finally {
            return $this->flats;
        }
    }

    /**
     *
     * @return array|Symfony\Component\DomCrawler\Crawler  The Crawler object, containing flat info
     */
    abstract protected function getFlatsArray();

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return Flat
     */
    abstract protected function getFlat($node): Flat;

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getPrice($node): ?string;

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getLink($node): ?string;

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getTimestamp($node): ?string;

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getDescription($node): ?string;

    /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getPhone($node): ?string;

     /**
     *
     * @param array|Symfony\Component\DomCrawler\Crawler $node
     * @return string|null
     */
    abstract protected function getAddress($node): ?string;
}
