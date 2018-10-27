<?php

require '../vendor/autoload.php';

use App\Controllers\MailController;
use App\Controllers\UpdatesController;
use App\Factories\SiteFactory;

$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

try {
    var_dump(SiteFactory::getSiteClassNames());
} catch (\Error $ex) {
    echo $ex->getMessage() . "<br>";
    echo $ex->getTraceAsString();
} catch (\Exception $ex) {
    echo $ex->getMessage() . "<br>";
    echo $ex->getTraceAsString();
}