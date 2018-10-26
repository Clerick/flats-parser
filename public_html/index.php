<?php

require '../vendor/autoload.php';

use App\Controllers\MailController;
use App\Controllers\UpdatesController;

$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

try {
//$updates = UpdatesController::getUpdates();
// MailController::sendMail($updates);
//echo "parsing was successful";
    $updates = UpdatesController::getSiteUpdate('KvartirantSite');
    var_dump($updates);
} catch (\Error $ex) {
    echo $ex->getMessage();
} catch (\Exception $ex) {
    echo $ex->getMessage();
}