<?php
class Flat
{
    public $price,
            $link,
            $address,
            $timestamp,
            $phone,
            $description;

    public function getYandexLink()
    {
        return "https://yandex.by/maps/157/minsk/?ll=27.653345%2C53.858359&z=13&mode=search&text=" . $address;
    }
}
