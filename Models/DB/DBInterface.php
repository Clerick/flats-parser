<?php

interface DBInterface
{
    function save(array $flats, $table_name);

    function get_all($table_name);

    function delete($id);

    function check();

    function uncheck();
}