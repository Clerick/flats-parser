<?php

class SQLDB implements DBInterface
{
    private $host = 'localhost';
    private $database = 'parse';
    private $user = 'root';
    private $pswd = '';
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
     * Compare input array of flats and save new flats
     * @param array $flats
     * @param $table_name
     */
    function save(array $flats, $table_name)
    {
        $flats_in_db = $this->getAll($table_name);

        $new_flats = array_diff($flats, $flats_in_db);

        $this->connect();

        foreach ($new_flats as $flat)
        {
            $stmt = $this->link->prepare("INSERT INTO `$table_name` (`price`, `link`, `address`, `timestamp`, `phone`, `description`) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("isssss", $flat->price, $flat->link, $flat->address, $flat->timestamp, $flat->phone, $flat->description);
            $stmt->execute();
            $stmt->close();
        }

        $this->link->close();
    }


    /**
     * Get all flats from
     * @param string $table_name
     * @return Flat[]
     */
    function getAll($table_name)
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

    function delete($id)
    {
        // TODO: Implement delete() method.
    }

    function check()
    {
        // TODO: Implement check() method.
    }

    function uncheck()
    {
        // TODO: Implement uncheck() method.
    }
}