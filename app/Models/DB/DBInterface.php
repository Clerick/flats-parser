<?php namespace App\Models\DB;

interface DBInterface
{
    public function getNewFlats(array $parsed_flats, $table_name);

    public function save(array $flats, $table_name);

    public function getAllFromTable($table_name);

    public function delete($id);

    public function check();

    public function uncheck();
}
