<?php

/*
 * Set error reporting to the max level.
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');

$autoloader = dirname(__DIR__) . '/vendor/autoload.php';
/*
 * Check that composer installation was done.
 */
if (!file_exists($autoloader)) {
    throw new Exception(
        'Please run "composer install" in root directory to setup unit test dependencies before running the tests'
    );
}
// Include the Composer autoloader.
require_once $autoloader;

// Load dotenv file
$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

/*
 * Unset global variables that are no longer needed.
 */
//unset($autoloader);

