<?php

namespace App\Controllers;

use App\Models\DB\DBInterface;
use App\Models\AbstractSite;

class UpdatesController
{

    /**
     *
     * @param AbstractSite $site
     * @param DBInterface $db
     * @return Flat[]
     */
    public static function getSiteUpdate(AbstractSite $site, DBInterface $db)
    {
        $parsed_flats = $site->getFlats();
        $new_flats = $db->getNewFlats($parsed_flats, $site->getClassName());

        return $new_flats;
    }

}
