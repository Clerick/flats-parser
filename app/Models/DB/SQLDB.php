<?php

namespace App\Models\DB;

use App\Models\DB\DatabaseConfiguration;
use App\Models\DB\DBInterface;
use App\Models\Flat;

class SQLDB implements DBInterface
{

    /**
     *
     * @var DatabaseConfiguration
     */
    private $configuration;

    /**
     *
     * @var resource
     */
    private $link;

    public function __construct(DatabaseConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Connect to DB
     */
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
     * @param array $parsed_flats
     * @param string $table_name
     * @return Flat[]
     */
    public function getNewFlats(array $parsed_flats, string $table_name)
    {
        if (!$this->tableExists($table_name)) {
            $this->createTable($table_name);
        }

        $flats_in_db = $this->getAllFromTable($table_name);
        $new_flats = array_diff($parsed_flats, $flats_in_db);
        $this->save($new_flats, $table_name);

        return $new_flats;
    }

    /**
     * Save flats to db
     * @param Flat[] $flats
     * @param string $table_name
     */
    public function save($flats, string $table_name)
    {
        $this->connect();

        foreach ($flats as $flat) {
            $stmt = $this->link->prepare("INSERT INTO `$table_name` (`price`, `link`, `timestamp`, `phone`, `description`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $flat->price, $flat->link, $flat->timestamp, $flat->phone, $flat->description);
            $stmt->execute();
            $stmt->close();
        }

        $this->link->close();
    }

    /**
     * Get all flats from db
     * @param string $table_name
     * @return Flat[]
     */
    public function getAllFromTable(string $table_name)
    {
        $flats = [];

        $this->connect();

        $stmt = $this->link->prepare("SELECT `id`, `price`, `link`, `timestamp`, `phone`, `description` FROM `" . $table_name . "` LIMIT 50");
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flat = new Flat();
            $flat->price = $row['price'];
            $flat->link = $row['link'];
            $flat->timestamp = $row['timestamp'];
            $flat->phone = $row['phone'];
            $flat->description = $row['description'];

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

    public function createTable(string $table_name)
    {
        $this->connect();
        $this->link->query("CREATE TABLE `" . $this->database . "`.`" . $table_name . "` ( `id` INT NOT NULL AUTO_INCREMENT , `price` VARCHAR(255) NULL , `link` TEXT NOT NULL ,`timestamp` VARCHAR(255) NOT NULL , `phone` VARCHAR(255) NULL , `description` TEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $this->link->close();
    }

}
