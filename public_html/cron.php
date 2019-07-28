<?php

// Load composer
require_once '../vendor/autoload.php';

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

// Add you bot's API key and name
$env_path = dirname(__FILE__, 2);
$dotenv = \Dotenv\Dotenv::create($env_path);
$dotenv->load();
$bot_api_key = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
$commands_path = dirname(__DIR__) . "/app/Models/TGBot/Commands/";

$telegram = new Telegram($bot_api_key, $bot_username);

// Get the chat id and message text from the CLI parameters.
$chat_id = 357496770;
$message = 'test cron';

if ($chat_id !== '' && $message !== '') {
    $data = [
        'chat_id' => $chat_id,
        'text'    => $message,
    ];

    $result = Request::sendMessage($data);

    if ($result->isOk()) {
        echo 'Message sent succesfully to: ' . $chat_id;
    } else {
        echo 'Sorry message not sent to: ' . $chat_id;
    }
}

