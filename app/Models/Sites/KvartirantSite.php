<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;

use Symfony\Component\DomCrawler\Crawler;

class KvartirantSite extends AbstractSite
{
    public function __construct()
    {
        $this->parse_url = 'https://www.kvartirant.by/ads/flats/rent/?tx_uedbadsboard_pi1%5Bsearch%5D%5Bq%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdistrict%5D=0&tx_uedbadsboard_pi1%5Bsearch%5D%5Brooms%5D%5B%5D=1&tx_uedbadsboard_pi1%5Bsearch%5D%5Brooms%5D%5B%5D=2&tx_uedbadsboard_pi1%5Bsearch%5D%5Brooms%5D%5B%5D=3&tx_uedbadsboard_pi1%5Bsearch%5D%5Brooms%5D%5B%5D=4&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bge%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Ble%5D=250&tx_uedbadsboard_pi1%5Bsearch%5D%5Bcurrency%5D%5Be%5D=840&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdate%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bagency_id%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bowner%5D=on';
        $this->name = 'kvartirant';
        parent::__construct();
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getFlatsArray() : Crawler
    {
        try {
            $crawler = $this->client->request('GET', $this->parse_url);
            $crawler = $crawler->filter('.bb-ad .bb-ad-item');

            return $crawler;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return new Crawler();
        }
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getFlat($node): Flat
    {
        $flat = new Flat();
        $flat->setPrice($this->getPrice($node));
        $flat->setLink($this->getLink($node));
        $flat->setTimestamp($this->getTimestamp($node));
        $flat->setDescription($this->getDescription($node));
        $flat->setPhone($this->getPhone($node));

        return $flat;
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getPrice($node): ?string
    {
        try {
            return trim($node->filter('.price')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getLink($node): ?string
    {
        try {
            return $node->filter('.title-obj a')->link()->getUri();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getTimestamp($node): ?string
    {
        try {
            return $node->filter('.data')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getDescription($node): ?string
    {
        try {
            return trim($node->filter('.bottom p')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getPhone($node): ?string
    {
        return null;
    }
}
