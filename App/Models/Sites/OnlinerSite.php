<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;

class OnlinerSite extends AbstractSite
{
     /**
     * @var string
     */
    protected $name = 'Onliner';

    protected function getFlatsArray() : ?array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->parse_url);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        return $result['apartments'];
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
            return $node['price']['amount'] . ' ' . $node['price']['currency'];
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getLink($node): ?string
    {
        try {
            return $node['url'];
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getTimestamp($node): ?string
    {
        try {
            return $node['last_time_up'];
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }

    protected function getDescription($node): ?string
    {
        try {
            $crawler = $this->client->request('GET', $this->getLink($node));
            return trim($crawler->filter('.apartment-info__cell_66 .apartment-info__sub-line_extended-bottom')->text());
        } catch (\Exception $e) {
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
            return isset($node['location']) ? $node['location']['address'] : null;
        } catch (\Exception $ex) {
            // TODO: log exception
            return null;
        }
    }
}
