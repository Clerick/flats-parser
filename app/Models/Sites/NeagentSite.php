<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;
use Symfony\Component\DomCrawler\Crawler;

class NeagentSite extends AbstractSite
{
    /**
     * @var string
     */
    protected $name = 'Neagent';

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

    protected function getFlat($node): Flat
    {
        $flat = new Flat();
        $flat->setPrice($this->getPrice($node));
        $flat->setLink($this->getLink($node));
        $flat->setTimestamp($this->getTimestamp($node));
        $flat->setDescription($this->getDescription($node));
        $flat->setPhone($this->getPhone($node));
        $flat->setAddress($this->getAddress($node));

        return $flat;
    }

    protected function getPrice($node): ?string
    {
        try {
            return $node->filter('.itm_price')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getLink($node): ?string
    {
        try {
            return $node->filter('.imd_photo a[href]')->attr('href');
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getTimestamp($node): ?string
    {
        try {
            return $node->filter('.md_head i')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getDescription($node): ?string
    {
        try {
            return trim($node->filter('.imd_mess')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getPhone($node): ?string
    {
        return null;
    }

    protected function getAddress($node): ?string
    {
        try {
            return trim($node->filter('.imd_mid .md_head em')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }
}
