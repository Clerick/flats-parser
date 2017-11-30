<?php

interface DBInterface
{
    function save(array $flats, $table_name);

    function getAll($table_name);

    function delete($id);

    function check();

    function uncheck();
}