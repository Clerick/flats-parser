<?php

namespace App\Model\Site;

use App\Model\Flat;

use Symfony\Component\DomCrawler\Crawler;

class KvartirantSite extends AbstractSite
{
    /**
     * @var string
     */
    protected $name = 'Kvartirant';

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
            return trim($node->filter('.price')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getLink($node): ?string
    {
        try {
            return $node->filter('.title-obj a')->link()->getUri();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getTimestamp($node): ?string
    {
        try {
            return $node->filter('.data')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getDescription($node): ?string
    {
        try {
            return trim($node->filter('.bottom p')->text());
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
            return trim($node->filter('.title-obj span')->text());
        } catch(\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }
}
