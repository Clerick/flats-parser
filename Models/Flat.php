<?php
class Flat
{
    public $price,
            $link,
            $address,
            $timestamp,
            $phone,
            $description;

    /**]
     * @return string
     */
    public function getYandexLink()
    {
        return "https://yandex.by/maps/157/minsk/?ll=27.653345%2C53.858359&z=13&mode=search&text=" . $this->address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        if ($this->phone == null)
        {
            return 'не спарсил';
        }
        return $this->phone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Цена: $this->price, адрес: $this->address";
    }

    /**
     * @return integer
     */
     public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
