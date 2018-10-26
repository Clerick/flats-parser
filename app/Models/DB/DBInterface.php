<?php

namespace App\Models\DB;

interface DBInterface
{

    /**
     *
     * @param array $parsed_flats
     * @param string $table_name
     * @return Flat[]
     */
    public function getNewFlats(array $parsed_flats, string $table_name);

    /**
     *
     * @param Flat[] $flats
     * @param string $table_name
     */
    public function save($flats, string $table_name);

    /**
     *
     * @param string $table_name
     * @return Flat[]
     */
    public function getAllFromTable(string $table_name);

    public function delete(int $id);
}
