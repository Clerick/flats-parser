<?php

use PHPUnit\Framework\TestCase;
use App\Models\Flat;

class FlatTest extends TestCase
{

    private $flat;

    public function SetUp()
    {
        $this->flat = new Flat();
    }

    public function propertiesProvider()
    {
        return [
            'not null properties' => [
                "100$",
                "https://some.site/flat/123",
                "10/20/2018 12:12",
                "+375447444444",
                "test description",
                "<b>Цена:</b> 100$" . PHP_EOL .
                "<a href='https://some.site/flat/123'>Ссылка</a>" . PHP_EOL .
                "<b>Время:</b> 10/20/2018 12:12" . PHP_EOL .
                "<b>Телефон:</b> +375447444444" . PHP_EOL .
                "<b>Описание:</b> test description" . PHP_EOL,
            ],
            'null properties' => [
                null,
                "https://some.site/flat/123",
                null,
                null,
                null,
                "<b>Цена:</b> нет" . PHP_EOL .
                "<a href='https://some.site/flat/123'>Ссылка</a>" . PHP_EOL .
                "<b>Время:</b> нет" . PHP_EOL .
                "<b>Телефон:</b> нет" . PHP_EOL .
                "<b>Описание:</b> нет" . PHP_EOL,
            ],
        ];
    }

    /**
     *
     * @dataProvider propertiesProvider
     */
    public function testToString($price, $link, $timestamp, $phone, $description, $expected)
    {
        $this->flat->setPrice($price);
        $this->flat->setLink($link);
        $this->flat->setTimestamp($timestamp);
        $this->flat->setPhone($phone);
        $this->flat->setDescription($description);

        $this->assertEquals($expected, $this->flat->__toString());
    }

}
