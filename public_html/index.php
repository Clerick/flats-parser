<?php

require '../vendor/autoload.php';

use App\Controllers\MailController;
use App\Controllers\UpdatesController;
use App\Factories\SiteFactory;

$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

try {
//$updates = UpdatesController::getUpdates();
// MailController::sendMail($updates);
//echo "parsing was successful";
//    $updates = UpdatesController::getSiteUpdate('KvartirantSite');
//    var_dump($updates);
    $sites = SiteFactory::getSitesArray();
    var_dump($sites);
} catch (\Error $ex) {
    echo $ex->getMessage() . "<br>";
    echo $ex->getTraceAsString();
} catch (\Exception $ex) {
    echo $ex->getMessage() . "<br>";
    echo $ex->getTraceAsString();
}