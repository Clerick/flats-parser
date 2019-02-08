<?php

namespace App\Models;

class Flat
{

    /**
     *
     * @var string
     */
    private $price;

    /**
     *
     * @var string
     */
    private $link;

    /**
     *
     * @var string
     */
    private $timestamp;

    /**
     *
     * @var string
     */
    private $phone;

    /**
     *
     * @var string
     */
    private $description;

    /**
     * @return string
     */
    public function __toString()
    {
        return "<b>Цена:</b> " . ($this->price != null ? "{$this->price}" : "нет") . PHP_EOL .
            "<a href='" . $this->link . "'>Ссылка</a>" . PHP_EOL .
            "<b>Дата размещения:</b> " . ($this->timestamp != null ? "{$this->timestamp}" : "нет") . PHP_EOL .
            "<b>Телефон:</b> " . ($this->phone != null ? "{$this->phone}" : "нет") . PHP_EOL .
            "<b>Описание:</b> " . ($this->description != null ? "{$this->description}" : "нет");
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string|null
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
     * @return string|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string|null $phone
     */
    public function setPhone(?string $phone)
    {
        $this->phone = $phone;
    }

    /**
     *
     * @param string|null $price
     */
    public function setPrice(?string $price)
    {
        $this->price = $price;
    }

    /**
     *
     * @param string|null $link
     */
    public function setLink(?string $link)
    {
        $this->link = $link;
    }

    /**
     *
     * @param string|null $timestamp
     */
    public function setTimestamp(?string $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     *
     * @param string|null $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

}
