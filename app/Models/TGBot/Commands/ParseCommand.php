<?php

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;
use App\Controllers\UpdatesController;
use App\Factories\SiteFactory;
use App\Utils\SiteUtil;
use App\Models\TGBot\MessageBuilder;
use App\Models\DB\DatabaseConfiguration;
use App\Models\DB\SQLDB;

class ParseCommand extends AdminCommand
{

    /**
     * @var string
     */
    protected $name = 'parse';

    /**
     * @var string
     */
    protected $description = 'Parse sites';

    /**
     * @var string
     */
    protected $usage = '/parse';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     *
     * @var array
     */
    protected $updates = [];

    /**
     *
     * @var DBInterface
     */
    protected $db;

    protected function initialize()
    {
        $db_config = new DatabaseConfiguration();
        $this->db = new SQLDB($db_config);
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text' => 'Начинаю парсинг',
        ];
        $result = Request::sendMessage($data);
        $message_id = $result->result->message_id;

        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action' => 'typing',
        ]);

        $this->initialize();
        $site_class_names = SiteUtil::getSiteClassNames();

        foreach ($site_class_names as $site_class_name) {
            try {
                $site = SiteFactory::build($site_class_name);
                $data = [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => 'Сижу на ' . SiteUtil::getSiteAlias($site_class_name),
                ];
                $result = Request::editMessageText($data);

                Request::sendChatAction([
                    'chat_id' => $chat_id,
                    'action' => 'typing',
                ]);

                $updates = UpdatesController::getSiteUpdate($site, $this->db);

                Request::sendChatAction([
                    'chat_id' => $chat_id,
                    'action' => 'typing',
                ]);
            } catch (\Exception $e) {
                $site->close();
                $data = [
                    'chat_id' => $chat_id,
                    'text' => "Произошла ошибка: " . $e->getMessage(),
                ];
                $result = Request::sendMessage($data);
                // TODO: Log errors
                var_dump($e->getMessage());
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => 'Парсинг закончен',
        ];
        $result = Request::editMessageText($data);


        return $result;
    }

}
