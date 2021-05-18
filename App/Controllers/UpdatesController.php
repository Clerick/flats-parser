<?php

namespace App\Controllers;

use App\Model\DB\DBInterface;
use App\Model\Site\AbstractSite;
use App\Model\Flat;

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
