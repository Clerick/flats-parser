<?php

/**
 * README
 * This file is intended to unset the webhook.
 * Uncommented parameters must be filled
 */
// Load composer
require_once  '../vendor/autoload.php';
// Add you bot's API key and name
$env_path = dirname(__FILE__, 2);
$dotenv = \Dotenv\Dotenv::create($env_path);
$dotenv->load();

$bot_api_key  = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    // Delete webhook
    $result = $telegram->deleteWebhook();
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}
