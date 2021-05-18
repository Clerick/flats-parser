<?php


namespace App\Model\Site;


use App\Model\Flat;
use Symfony\Component\DomCrawler\Crawler;

class KufarSite extends AbstractSite
{
    /**
     * @var string
     */
    protected $name = 'Kufar';

    protected function getFlatsArray()
    {
        try {
            $crawler = $this->client->request('GET', $this->parse_url);
            $crawler = $crawler->filterXPath('//main//article//a');

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
            return $node->filterXPath('//*/div[2]/div/div/span[1]')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getLink($node): ?string
    {
        return $node->attr('href');
    }

    protected function getTimestamp($node): ?string
    {
        try {
            return $node->filterXPath('//*/div[1]/div[2]/span')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getDescription($node): ?string
    {
        try {
            return $node->filterXPath('//*/div[2]/div[3][not(div)]')->text();
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
            return $node->filterXPath('//*/div[2]/div[last()]/span')->text();
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }
}
