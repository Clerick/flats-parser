<?php

class HomeController
{
    private $DB;

    public function _construct()
    {
        $this->DB= new SQLDB();
    }

    public function getUpdates()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Sites';
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            $class = str_replace('.php', '', $file);
            $variable = strtolower($class);

            $$variable = new $class();
        }

    }
}