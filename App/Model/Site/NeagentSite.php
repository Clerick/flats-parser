<?php

namespace App\Model\Site;

use App\Model\Flat;
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
            $crawler = $crawler->filter('.c-card__description');

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
            return trim($node->filter('.price')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getLink($node): ?string
    {
        try {
            return $node->filter('a.c-card__title')->attr('href');
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getTimestamp($node): ?string
    {
        try {
            return $node->filter('span.date')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getDescription($node): ?string
    {
        try {
            return trim($node->filter('.c-card__mess')->text());
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
            return trim($node->filter('.c-card__addr')->text());
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }
}
