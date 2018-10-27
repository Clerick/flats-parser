<?php

require '../vendor/autoload.php';

$env_path = dirname(__FILE__, 2);
$dotenv = new \Dotenv\Dotenv($env_path);
$dotenv->load();

$bot_api_key  = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
$hook_url     = getenv('BOT_HOOK_URL');

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}