<?php

require '../vendor/autoload.php';

$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

$bot_api_key = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
$commands_path = dirname(__DIR__) . "/app/Models/TGBot/Commands/";
$admin_users = [
    357496770,
    471334467
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $telegram->addCommandsPath($commands_path);
    $telegram->enableAdmins($admin_users);
    $telegram->enableLimiter();

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
     echo $e->getMessage();
}