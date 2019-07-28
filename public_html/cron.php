<?php

// Load composer
require_once '../vendor/autoload.php';

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use App\Models\DB\DatabaseConfiguration;
use App\Models\DB\SQLDB;
use App\Utils\SiteUtil;
use App\Models\TGBot\MessageBuilder;
use App\Factories\SiteFactory;
use App\Controllers\UpdatesController;

$env_path = dirname(__FILE__, 2);
$dotenv = \Dotenv\Dotenv::create($env_path);
$dotenv->load();
$bot_api_key = getenv('BOT_TOKEN');
$bot_username = getenv('BOT_USERNAME');
$commands_path = dirname(__DIR__) . "/app/Models/TGBot/Commands/";
$dbConfig = new DatabaseConfiguration();
$db = new SQLDB($dbConfig);
$telegram = new Telegram($bot_api_key, $bot_username);
$siteClassNames = SiteUtil::getSiteClassNames();
$chat_ids = [
    357496770,
    471334467
];

$telegram->enableLimiter();

foreach ($siteClassNames as $siteClassName) {
    $site = SiteFactory::build($siteClassName);
    $updates = UpdatesController::getSiteUpdate($site, $db);

    if($updates) {
        $message = MessageBuilder::build(SiteUtil::getSiteAlias($siteClassName), $updates);

        foreach ($chat_ids as $chat_id) {
            if ($chat_id !== '' && $message !== '') {
                $data = [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ];

                $result = Request::sendMessage($data);
            }
        }
    }
}
