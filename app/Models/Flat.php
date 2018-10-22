<?php namespace App\Models;

class Flat
{
    public $price;
    public $link;
    public $timestamp;
    public $phone;
    public $description;

    /**
     * @return string
     */
    public function getPhone()
    {
        if ($this->phone == null) {
            return 'не спарсил';
        }
        return $this->phone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Цена: " . ($this->price != null ? "{$this->price}\r\n" : "нет\r\n") .
                "Ссылка: $this->link\r\n" .
                "Время: " . ($this->timestamp != null ? "{$this->timestamp}\r\n" : "нет\r\n") .
                "Телефон: " . ($this->phone != null ? "{$this->phone}\r\n" : "нет\r\n") .
                "Описание: " . ($this->description != null ? "{$this->description}\r\n" : "нет") .
                "\r\n";
    }

    /**
     * @return string
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
