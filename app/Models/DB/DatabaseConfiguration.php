<?php

namespace App\Models\DB;

class DatabaseConfiguration
{

    /**
     *
     * @var string
     */
    private $host = 'localhost';

    /**
     *
     * @var string
     */
    private $database = 'flats-parser';

    /**
     *
     * @var string
     */
    private $user = 'root';

    /**
     *
     * @var string
     */
    private $pswd = '1234';

    public function __construct(string $host, string $database, string $user, string $pswd)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->pswd = $pswd;
    }

    /**
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return string
     */
    public function getPswd()
    {
        return $this->pswd;
    }

}
