<?php

require_once __DIR__ . '/../vendor/autoload.php';

// use App\Controllers\MailController;
use App\Controllers\UpdatesController;
use App\Factories\SiteFactory;
use App\Utils\DbUtil;

$env_path = dirname(__FILE__, 2);
$dotenv = \Dotenv\Dotenv::create($env_path);
$dotenv->load();

$conf = new \App\Model\DB\DatabaseConfiguration();
$db = new App\Model\DB\SQLDB($conf);

//$site = SiteFactory::build('NeagentSite');
//$site = SiteFactory::build('KvartirantSite');
//$site = SiteFactory::build('OnlinerSite');
//$site = SiteFactory::build('KufarSite');

//$updates = UpdatesController::getSiteUpdate($site, $db);
//var_dump($updates);
//DbUtil::createTablesForAllSites();
