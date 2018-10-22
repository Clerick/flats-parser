<?php namespace App\Models;

use App\Models\Flat;

class Mail
{
    public $to = "13_spirit@mail.ru";
    public $subject = "Парсер квартир";
    public $message = "";

    public function __construct($flats_array)
    {
        foreach ($flats_array as $site => $flats) {
            $this->message .="----" . $site . "----" . "\r\n";
            foreach ($flats as $flat) {
                $this->message .= $flat;
                $this->message .= "\r\n";
            }
            $this->message .= "---------------\r\n\r\n";
        }
    }
}
