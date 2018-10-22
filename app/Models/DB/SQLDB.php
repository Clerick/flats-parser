<?php namespace App\Models\DB;

use App\Models\DB\DBInterface;
use App\Models\Flat;

class SQLDB implements DBInterface
{
    private $host     = 'localhost';
    private $database = 'flats-parser';
    private $user     = 'root';
    private $pswd     = '1234';
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
    public function getNewFlats(array $parsed_flats, $table_name)
    {
        if (!$this->tableExists($table_name)) {
            $this->createTable($table_name);
        }

        $new_flats = null;

        $flats_in_db = $this->getAllFromTable($table_name);
        $new_flats = array_diff($parsed_flats, $flats_in_db);
        $this->save($new_flats, $table_name);

        return $new_flats;
    }

    /**
     * Save flats to db
     * @param array $flats
     * @param $table_name
     */
    public function save(array $flats, $table_name)
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
    public function getAllFromTable($table_name)
    {
        $flats = [];

        $this->connect();

        $stmt = $this->link->prepare("SELECT * FROM $table_name");
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flat              = new Flat();
            $flat->price       = $row['price'];
            $flat->link        = $row['link'];
            $flat->timestamp   = $row['timestamp'];
            $flat->phone       = $row['phone'];
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
    /**
     * Check if table exists in database
     *
     * @param string $table_name
     * @return boolean
     */
    public function tableExists($table_name) : bool
    {
        $this->connect();
        if ($result = $this->link->query("SHOW TABLES LIKE '" . $table_name . "'")) {
            if ($result->num_rows === 1) {
                return true;
            }
        }
        return false;
    }

    public function createTable($table_name)
    {
        $this->connect();
        $this->link->query("CREATE TABLE `" . $this->database . "`.`" . $table_name . "`" . "( `id` INT NOT NULL AUTO_INCREMENT , `price` VARCHAR(255) NULL , `link` TEXT NOT NULL ,`timestamp` VARCHAR(255) NOT NULL , `phone` VARCHAR(255) NULL , `description` TEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
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
