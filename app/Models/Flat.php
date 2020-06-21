<?php

declare(strict_types = 1);

namespace App\Models;

class Flat
{
    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $description;

    public function __toString() : string
    {
        return "<b>Цена:</b> " . ($this->price != null ? "{$this->price}" : "нет") . PHP_EOL
            . "<a href='" . $this->link . "'>Ссылка</a>" . PHP_EOL
            . "<b>Адрес:</b>" . ($this->address != null ? "{$this->address}" : 'нет') . PHP_EOL
            . "<b>Дата размещения:</b> " . ($this->timestamp != null ? "{$this->timestamp}" : "нет") . PHP_EOL
            . "<b>Телефон:</b> " . ($this->phone != null ? "{$this->phone}" : "нет") . PHP_EOL
            . "<b>Описание:</b> " . ($this->description != null ? "{$this->description}" : "нет");
    }

    public function getPrice() : ?string
    {
        return $this->price;
    }

    public function getLink() : string
    {
        return $this->link;
    }

    public function getTimestamp() : ?string
    {
        return $this->timestamp;
    }

    public function getPhone() : ?string
    {
        return $this->phone;
    }

    public function getAddress() : ?string
    {
        return $this->address;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setPhone(?string $phone)
    {
        $this->phone = $phone;
    }

    public function setAddress(?string $address) {
        $this->address = $address;
    }

    public function setPrice(?string $price)
    {
        $this->price = $price;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }

    public function setTimestamp(?string $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }
}
