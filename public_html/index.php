<?php

require '../vendor/autoload.php';

use App\Controllers\MailController;
use App\Controllers\UpdatesController;

try {

//$updates = UpdatesController::getUpdates();
// MailController::sendMail($updates);
//echo "parsing was successful";
//$updates = UpdatesController::getSiteUpdate('OnlinerSite');
//var_dump($updates);

    $site = new \App\Models\Sites\OnlinerSite();
//    $site = new \App\Models\Sites\KvartirantSite();
//    $site = new App\Models\Sites\NeagentSite();
    var_dump($site->getFlats());
} catch (Exception $ex) {
    var_dump($ex);
}