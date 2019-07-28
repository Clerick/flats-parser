<?php

namespace App\Controllers;

use App\Models\DB\DBInterface;
use App\Models\AbstractSite;
use App\Models\Flat;

class UpdatesController
{

    /**
     *
     * @param AbstractSite $site
     * @param DBInterface $db
     * @return Flat[]
     */
    public static function getSiteUpdate(AbstractSite $site, DBInterface $database)
    {
        $parsedFlats = $site->getFlats();
        $newFlats = $database->getNewFlats($parsedFlats, $site->getClassName());

        return $newFlats;
    }
}
