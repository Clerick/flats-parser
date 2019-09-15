<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Symfony\Component\DomCrawler\Crawler;

class NeagentSite extends AbstractSite
{
    public function __construct()
    {
        $this->parse_url = 'https://neagent.by/board/minsk/?catid=1&priceMin=180&priceMax=310&currency=2';
        $this->name = 'neagent';
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
            $crawler = $crawler->filter('.imd');

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
            return $node->filter('.itm_price')->text();
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
            return $node->filter('.imd_photo a[href]')->attr('href');
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
            return $node->filter('.md_head i')->text();
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
            return trim($node->filter('.imd_mess')->text());
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
