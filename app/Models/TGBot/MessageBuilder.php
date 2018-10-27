<?php

namespace App\Models\TGBot;

class MessageBuilder
{

    /**
     *
     * @param string $site_name
     * @param Flat[] $flats
     * @return string
     */
    public static function build(string $site_name, $flats): string
    {
        $message = "---- $site_name ----" . PHP_EOL;

        if (empty($flats)) {
            $message .= "Обновлений нет";
            return $message;
        }

        foreach ($flats as $flat) {
            $message .= $flat . PHP_EOL;
        }

        return $message;
    }

}
