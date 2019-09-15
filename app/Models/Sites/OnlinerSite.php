<?php

namespace App\Models\Sites;

use App\Models\AbstractSite;
use App\Models\Flat;

class OnlinerSite extends AbstractSite
{
    public function __construct()
    {
        // ajax request
        $this->parse_url = 'https://ak.api.onliner.by/search/apartments?rent_type%5B%5D=2_rooms&rent_type%5B%5D=3_rooms&rent_type%5B%5D=4_rooms&rent_type%5B%5D=5_rooms&rent_type%5B%5D=6_rooms&price%5Bmin%5D=180&price%5Bmax%5D=320&currency=usd&only_owner=true&bounds%5Blb%5D%5Blat%5D=53.82523615329648&bounds%5Blb%5D%5Blong%5D=27.357659697716585&bounds%5Brt%5D%5Blat%5D=53.97348638507377&bounds%5Brt%5D%5Blong%5D=27.733104014794772&page=1&v=0.5541089302276405';
        $this->name = 'onliner';
        parent::__construct();
    }

    /**
     *
     * {@inheritDoc}
     */
    protected function getFlatsArray() : array
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
            return $node['price']['amount'] . ' ' . $node['price']['currency'];
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
            return $node['url'];
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
            return $node['last_time_up'];
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
            $crawler = $this->client->request('GET', $this->getLink($node));
            return $crawler->filter('.apartment-info__cell_66 .apartment-info__sub-line_extended-bottom')->text();
        } catch (\Exception $e) {
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
