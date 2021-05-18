<?php

namespace App\Model\DB;

use App\Model\DB\DatabaseConfiguration;
use App\Model\DB\DBInterface;
use App\Model\Flat;

class SQLDB implements DBInterface
{

    /**
     *
     * @var DatabaseConfiguration
     */
    private $configuration;

    /**
     *
     * @var mysqli|bool
     */
    private $link;

    public function __construct(DatabaseConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function connect()
    {
        $this->link = mysqli_connect(
                $this->configuration->getHost(),
                $this->configuration->getUser(),
                $this->configuration->getPassword(),
                $this->configuration->getDatabase()
            ) or die(mysqli_connect_error());
        $this->link->query("SET NAMES utf8");
    }

    /**
     * Compare parsed flats with flats in db, save and return new flats
     * @param Flat[] $parsed_flats
     * @param string $table_name
     * @return Flat[]
     */
    public function getNewFlats(array $parsed_flats, string $table_name)
    {
        if (!$this->tableExists($table_name)) {
            $this->createTable($table_name);
        }

        $old_flats = $this->getOldFlats($table_name);
        $new_flats = array_diff($parsed_flats, $old_flats);
        $this->save($new_flats, $table_name);

        return $new_flats;
    }

    /**
     * Save flats to db
     * @param Flat[] $flats
     * @param string $table_name
     */
    public function save(array $flats, string $table_name)
    {
        $this->connect();
        $parse_time = date("Y-m-d H:i:s");

        foreach ($flats as $flat) {
            $price = $flat->getPrice();
            $link = $flat->getLink();
            $timestamp = $flat->getTimestamp();
            $phone = $flat->getPhone();
            $address = $flat->getAddress();
            $description = $flat->getDescription();
            $stmt = $this->link->prepare(
                "INSERT INTO `$table_name` ("
                . '`price`, '
                . '`link`, '
                . '`timestamp`, '
                . '`parse_time`, '
                . '`phone`, '
                . '`address`, '
                . '`description`'
                . ') VALUES (?,?,?,?,?,?,?)');
            $stmt->bind_param(
                'sssssss',
                $price,
                $link,
                $timestamp,
                $parse_time,
                $phone,
                $address,
                $description
            );
            $stmt->execute();
            $stmt->close();
        }

        $this->link->close();
    }

    /**
     *
     * Get flats from last parsing
     * @param string $table_name
     * @return Flat[]
     */
    public function getLastUpdate(string $table_name)
    {
        $this->connect();
        $flats = [];
        $stmt = $this->link->prepare(
            'SELECT `id`, '
            . '`price`, '
            . '`link`, '
            . '`timestamp`, '
            . '`parse_time`, '
            . '`phone`, '
            . '`address`, '
            . '`description` '
            . 'FROM `' . $table_name . '` '
            . 'WHERE `parse_time` LIKE '
            . '(SELECT MAX(`parse_time`) FROM `' . $table_name . '`)'
        );
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flat = $this->associateFlatWith($row);
            array_push($flats, $flat);
        }

        $stmt->close();
        $this->link->close();

        return $flats;
    }

    /**
     *
     * @param string $table_name
     * @return Flat[] Description
     */
    public function getOldFlats(string $table_name)
    {
        $this->connect();
        $flats = [];
        $stmt = $this->link->prepare(
            'SELECT `id`, '
            . '`price`, '
            . '`link`, '
            . '`timestamp`, '
            . '`parse_time`, '
            . '`phone`, '
            . '`address`, '
            . '`description` '
            . 'FROM `' . $table_name . '` ORDER BY `parse_time` DESC LIMIT 100'
        );
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flat = $this->associateFlatWith($row);
            array_push($flats, $flat);
        }

        $stmt->close();
        $this->link->close();

        return $flats;
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Check if table exists in database
     *
     * @param string $table_name
     * @return boolean
     */
    public function tableExists(string $table_name): bool
    {
        $this->connect();

        if ($result = $this->link->query("SHOW TABLES LIKE '" . $table_name . "'")) {
            if ($result->num_rows == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param string $table_name
     */
    public function createTable(string $table_name)
    {
        $this->connect();
        $this->link->query(
            "CREATE TABLE IF NOT EXISTS `" . $this->configuration->getDatabase() . "`.`" . $table_name
            . "` ( `id` INT NOT NULL AUTO_INCREMENT ,"
            . " `price` VARCHAR(255) NULL ,"
            . " `link` TEXT NOT NULL ,"
            . " `timestamp` VARCHAR(255) NULL ,"
            . " `parse_time` DATETIME NOT NULL ,"
            . " `phone` VARCHAR(255) NULL ,"
            . " `address` VARCHAR(255) NULL ,"
            . " `description` TEXT NULL ,"
            . " PRIMARY KEY (`id`)) ENGINE = InnoDB;"
        );
        $this->link->close();
    }

    /**
     *
     * @param array $row
     * @return Flat
     */
    private function associateFlatWith(array $row): Flat
    {
        $flat = new Flat();
        $flat->setPrice($row['price']);
        $flat->setLink($row['link']);
        $flat->setTimestamp($row['timestamp']);
        $flat->setPhone($row['phone']);
        $flat->setDescription($row['description']);
        $flat->setAddress($row['address']);

        return $flat;
    }

}
