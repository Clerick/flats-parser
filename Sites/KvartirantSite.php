<?php
use \Curl\Curl;
use \DiDom\Document;

class KvartirantSite extends Site
{
    public function __construct()
    {
        parent::__construct();
        $this->parseUrl = "https://www.kvartirant.by/ads/flats/type/rent/?tx_uedbadsboard_pi1%5Bsearch%5D%5Bq%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdistrict%5D=0&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bfrom%5D=50&tx_uedbadsboard_pi1%5Bsearch%5D%5Bprice%5D%5Bto%5D=180&tx_uedbadsboard_pi1%5Bsearch%5D%5Bcurrency%5D=840&tx_uedbadsboard_pi1%5Bsearch%5D%5Bdate%5D=259200&tx_uedbadsboard_pi1%5Bsearch%5D%5Bagency_id%5D=&tx_uedbadsboard_pi1%5Bsearch%5D%5Bowner%5D=on";
    }

    function get_flats()
    {
        $markup = $this->get_markup();

        if($markup == null)
        {
            return $this->error;
        }

        $document = new Document($markup);
        $rows = $document->find('table.ads_list_table tr');

        foreach($rows as $row)
        {
            try {
                $flat = new Flat();

                $price_box = $row->find('.price-box');
                if (empty($price_box)) {
                    continue;
                }
                $flat->price = $price_box[0]->text();

                $flat->link = $row->find('.title a')[0]->attr('href');
                $flat->timestamp = $row->find('.date')[0]->text();
                // $flat->description = $row->find(.)

                echo $flat->timestamp . "<br />";
                // $flat->link = $link_box[0]->attr('href');


            } catch (Exception $e) {
                var_dump($e);
            }


        }
    }

    protected function get_markup()
    {
        $data = null;

        $this->curl->get($this->parseUrl);

        if($this->curl->error)
        {
            $this->error = $this->curl->errorMessage;
        } else {
            $data = $this->curl->response;
        }

        $this->curl->close();

        return $data;
    }


}
