<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;

use Symfony\Component\DomCrawler\Crawler;

class KvartirantSite extends AbstractSite
{
    public function __construct()
    {
        $this->parse_url = 'https://www.kvartirant.by/ads/flats/type/rent/?tx_uedbadsboard_pi1%5Bsearch%5D%5Bq%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdistrict%5D=0&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bfrom%5D=180&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bto%5D=210&tx_uedbadsboard_pi1%5Bsearch%5D%5Bcurrency%5D=840&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdate%5D=86400&tx_uedbadsboard_pi1%5Bsearch%5D%5Bagency_id%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bowner%5D=on';
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
            $crawler = $crawler->filter('.ads_list_table tr[class]');

            return $crawler;
        } catch (\Exception $e) {
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
            return $node->filter('.price-box')->text();
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
            return $node->selectLink($node->filter('.title a')->text())->link()->getUri();
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
            return $node->filter('.date')->text();
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
            return $node->filter('.adtxt_box a:not(.ad_button) + p')->text();
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
