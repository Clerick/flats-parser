<?php

namespace App\Model\DB;

use App\Model\Flat;

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
    public function save(array $flats, string $table_name);

    /**
     *
     * @param string $table_name
     * @return Flat[]
     */
    public function getLastUpdate(string $table_name);

    public function delete(int $id);
}
