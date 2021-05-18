<?php

namespace App\Models\DB;

class DatabaseConfiguration
{

    /**
     *
     * @var string
     */
    private $host;

    /**
     *
     * @var string
     */
    private $database;

    /**
     *
     * @var string
     */
    private $user;

    /**
     *
     * @var string
     */
    private $password;

    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST');
        $this->database = getenv('MYSQL_DATABASE');
        $this->user = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_USER_PASSWORD');
    }

    /**
     *
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     *
     * @return string
     */
    public function getDatabase() : string
    {
        return $this->database;
    }

    /**
     *
     * @return string
     */
    public function getUser() : string
    {
        return $this->user;
    }

    /**
     *
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

}
