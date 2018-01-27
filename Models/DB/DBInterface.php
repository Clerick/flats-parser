<?php

interface DBInterface
{
    public function get_new_flats(array $parsed_flats, $table_name);

    public function save(array $flats, $table_name);

    public function get_all($table_name);

    public function delete($id);

    public function check();

    public function uncheck();
}