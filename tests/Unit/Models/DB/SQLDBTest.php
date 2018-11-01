<?php

use PHPUnit\Framework\TestCase;
use App\Models\DB\SQLDB;
use App\Models\DB\DatabaseConfiguration;
use App\Models\Flat;

class SQLDBTest extends TestCase
{

    /**
     *
     * Tested class object
     * @var SQLDB
     */
    private $sqldb;

    /**
     *
     * @var DatabaseConfiguration
     */
    private $db_config;

    /**
     *
     * @var resource
     */
    private $link;

    /**
     *
     * @var string
     */
    private $table_names;

    public function SetUp()
    {
        $this->db_config = new DatabaseConfiguration();
        $this->sqldb = new SQLDB($this->db_config);

        $this->link = mysqli_connect(
                $this->db_config->getHost(),
                $this->db_config->getUser(),
                $this->db_config->getPassword(),
                $this->db_config->getDatabase()
            ) or die(mysqli_connect_error());
        $this->link->query("SET NAMES utf8");

        $this->table_names = [
            "KvartirantSite",
            "NeagentSite",
            "OnlinerSite",
        ];
    }

    public function TearDown()
    {
        foreach ($this->table_names as $table_name) {
            $this->deleteTestFlatFromDb($table_name);
        }
        $this->link->close();
    }

    /**
     *
     * @dataProvider flatDataProvider
     */
    public function testSave(?string $price, string $link, ?string $timestamp, ?string $phone, ?string $description)
    {
        foreach ($this->table_names as $table_name) {
            $flat_to_save = $this->createFlatMock($price, $link, $timestamp, $phone, $description);
            $this->sqldb->save([$flat_to_save], $table_name);
            $flat_from_db = $this->getTestFlatFromDatabase($table_name, $flat_to_save->getLink());

            $this->assertEquals($flat_to_save->getPrice(), $flat_from_db->getPrice());
            $this->assertEquals($flat_to_save->getLink(), $flat_from_db->getLink());
            $this->assertEquals($flat_to_save->getTimestamp(), $flat_from_db->getTimestamp());
            $this->assertEquals($flat_to_save->getPhone(), $flat_from_db->getPhone());
            $this->assertEquals($flat_to_save->getDescription(), $flat_from_db->getDescription());
        }
    }

    /**
     * @dataProvider flatDataProvider
     */
    public function testGetNewFlats(?string $price, string $link, ?string $timestamp, ?string $phone, ?string $description)
    {
        foreach ($this->table_names as $table_name) {
            $expected_new_flat = $this->createFlatMock($price, $link, $timestamp, $phone, $description);
            $new_flats_from_db = $this->sqldb->getNewFlats([$expected_new_flat], $table_name);

            // Expects that count of new flat from db is equal 1
            $this->assertEquals(1, count($new_flats_from_db));
        }
    }

    public function flatDataProvider()
    {
        return [
            "not null flat" => [
                "250 BYN",
                "http://kvartirant.by/rent/test",
                "2 часа назад",
                "+375 25 9559999",
                "test",
            ],
            "null flat" => [
                null,
                "http://some.by/test",
                null,
                null,
                null,
            ],
        ];
    }

    public function testGetLastUpdate()
    {
        foreach ($this->table_names as $table_name) {
            $flats_array = $this->sqldb->getLastUpdate($table_name);
            $expected_count = $this->getLastUpdateCountFromDb($table_name);
            $this->assertEquals($expected_count, count($flats_array));
        }
    }

    /**
     *
     * @param string $price
     * @param string $link
     * @param string $timestamp
     * @param string $phone
     * @param string $description
     * @return type
     */
    private function createFlatMock(?string $price, string $link, ?string $timestamp, ?string $phone, ?string $description)
    {
        $flat_mock = $this->createMock(Flat::class);
        $flat_mock->method('getPrice')->willReturn($price);
        $flat_mock->method('getLink')->willReturn($link);
        $flat_mock->method('getTimestamp')->willReturn($timestamp);
        $flat_mock->method('getPhone')->willReturn($phone);
        $flat_mock->method('getDescription')->willReturn($description);

        return $flat_mock;
    }

    private function getTestFlatFromDatabase(string $table_name, string $link)
    {
        $stmt = $this->link->prepare(
            "SELECT `id`, " .
            "`price`, " .
            "`link`, " .
            "`timestamp`, " .
            "`parse_time`, " .
            "`phone`, " .
            "`description` " .
            "FROM `" . $table_name . "` WHERE `link` = '" . $link . "'"
        );
        $stmt->execute();

        $result = $stmt->get_result();


        $flat = $this->createMock(Flat::class);
        while ($row = $result->fetch_assoc()) {
            $flat->method('getPrice')->willReturn($row['price']);
            $flat->method('getLink')->willReturn($row['link']);
            $flat->method('getTimestamp')->willReturn($row['timestamp']);
            $flat->method('getPhone')->willReturn($row['phone']);
            $flat->method('getDescription')->willReturn($row['description']);
        }

        $stmt->close();

        return $flat;
    }

    /**
     *
     * @param string $table_name
     * @param string $description
     * @return bool
     */
    private function deleteTestFlatFromDb(string $table_name): bool
    {
        $stmt = $this->link->prepare(
            "DELETE FROM `" . $table_name .
            "` WHERE `link` LIKE '%test%'"
        );
        if (!$stmt) {
            die("SQL Error: " . $this->link->error);
        }

        return $stmt->execute();
    }

    private function getLastUpdateCountFromDb(string $table_name)
    {
        $result = $this->link->query(
            "SELECT COUNT(id) as total FROM `$table_name` WHERE parse_time LIKE (SELECT MAX(parse_time) FROM `$table_name`)"
        );

        $count = $result->fetch_assoc();

        return $count['total'];
    }

}
