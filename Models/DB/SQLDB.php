<?php

class SQLDB implements DBInterface
{
    private $host = 'mysql.hostinger.ru';
    private $database = 'u461096505_parse';
    private $user = 'u461096505_spirt';
    private $pswd = '1324123123';
    private $link;

    /**
     * Connect to DB
     */
    protected function connect()
    {
        $this->link = mysqli_connect($this->host, $this->user, $this->pswd) or die("Не могу соединиться с MySQL.");
        $this->link->select_db($this->database) or die("Не могу подключиться к базе.");
        $this->link->query("SET NAMES utf8");
    }

    /**
     * Compare parsed flats with flats in db, save and return new flats
     * @param array $parsed_flats
     * @param string $table_name
     * @return Flat[]
     */
    public function get_new_flats(array $parsed_flats, $table_name)
    {
        $new_flats = null;

        $flats_in_db = $this->get_all($table_name);

        $new_flats = array_diff($parsed_flats, $flats_in_db);

        $this->save($new_flats, $table_name);

        return $new_flats;
    }

    /**
     * Save flats to db
     * @param array $flats
     * @param $table_name
     */
    function save(array $flats, $table_name)
    {
//        $flats_in_db = $this->get_all($table_name);
//
//        $new_flats = array_diff($flats, $flats_in_db);
        $this->connect();

        foreach ($flats as $flat)
        {
            $stmt = $this->link->prepare("INSERT INTO `$table_name` (`price`, `link`, `address`, `timestamp`, `phone`, `description`) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("isssss", $flat->price, $flat->link, $flat->address, $flat->timestamp, $flat->phone, $flat->description);
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
    public function get_all($table_name)
    {
        $flats = [];

        $this->connect();

        $stmt = $this->link->prepare("SELECT * FROM `$table_name` ");
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flat = new Flat();
            $flat->price = $row['price'];
            $flat->link = $row['link'];
            $flat->address = $row['address'];
            $flat->timestamp = $row['timestamp'];
            $flat->phone = $row['phone'];
            $flat->description = $row['description'];

            array_push($flats, $flat);
        }

        $stmt->close();
        $this->link->close();

        return $flats;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function create_table($table_name)
    {
        $this->connect();
        $this->link->query("CREATE TABLE `parse`.`$table_name` ( `id` INT NOT NULL AUTO_INCREMENT , `price` INT NOT NULL , `link` TEXT NOT NULL , `address` VARCHAR(255) NOT NULL , `timestamp` VARCHAR(255) NOT NULL , `phone` VARCHAR(255) NULL , `description` VARCHAR(255) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $this->link->close();
    }

    public function check()
    {
        // TODO: Implement check() method.
    }

    public function uncheck()
    {
        // TODO: Implement uncheck() method.
    }
}