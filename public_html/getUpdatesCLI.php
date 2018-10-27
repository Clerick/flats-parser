#!/usr/bin/env php
<?php
/**
 * README
 * This configuration file is intended to run the bot with the getUpdates method.
 * Uncommented parameters must be filled
 *
 * Bash script:
 * $ while true; do ./getUpdatesCLI.php; done
 */
// Load composer
require_once '../vendor/autoload.php';
// Add you bot's API key and name
$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();
$bot_api_key = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
$commands_path = dirname(__DIR__) . "/app/Models/TGBot/Commands/";
// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
    357496770,
];
// Define all paths for your custom commands in this array (leave as empty array if not used)
//$commands_paths = [
//  __DIR__ . '/Commands/',
//];
// Enter your MySQL database credentials
//$mysql_credentials = [
//    'host'     => 'localhost',
//    'user'     => 'dbuser',
//    'password' => 'dbpass',
//    'database' => 'dbname',
//];
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    // Add commands paths containing your custom commands
    $telegram->addCommandsPath($commands_path);
    // Enable admin users
    $telegram->enableAdmins($admin_users);
    // Enable MySQL
//    $telegram->enableMySql($mysql_credentials);
    $telegram->useGetUpdatesWithoutDatabase();
    // Logging (Error, Debug and Raw Updates)
    //Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
    //Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
    //Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");
    // If you are using a custom Monolog instance for logging, use this instead of the above
    //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);
    // Set custom Upload and Download paths
    //$telegram->setDownloadPath(__DIR__ . '/Download');
    //$telegram->setUploadPath(__DIR__ . '/Upload');
    // Here you can set some command specific parameters
    // e.g. Google geocode/timezone api key for /date command
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);
    // Botan.io integration
    //$telegram->enableBotan('your_botan_token');
    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();
    // Handle telegram getUpdates request
    $server_response = $telegram->handleGetUpdates();
    if ($server_response->isOk()) {
        $update_count = count($server_response->getResult());
        echo date('Y-m-d H:i:s', time()) . ' - Processed ' . $update_count . ' updates';
    } else {
        echo date('Y-m-d H:i:s', time()) . ' - Failed to fetch updates' . PHP_EOL;
        echo $server_response->printError();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Catch log initialisation errors
    echo $e->getMessage();
}
