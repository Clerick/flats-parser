<?php

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;
use App\Controllers\UpdatesController;
use App\Factories\SiteFactory;

class ParseCommand extends AdminCommand
{

    /**
     * @var string
     */
    protected $name = 'start';

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

        $site_class_names = SiteFactory::getSiteClassNames();

        foreach ($site_class_names as $site_name) {
            $site = SiteFactory::build($site_name);
            try {
                $data = [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => 'Сижу на ' . $site->getSiteName(),
                ];
                $result = Request::editMessageText($data);

                Request::sendChatAction([
                    'chat_id' => $chat_id,
                    'action' => 'typing',
                ]);

                $updates = UpdatesController::getSiteUpdate($site);

                $text = \App\Models\TGBot\MessageBuilder::build($site->getSiteName(), $updates);
                Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

                Request::sendChatAction([
                    'chat_id' => $chat_id,
                    'action' => 'typing',
                ]);
            } catch (\Exception $e) {
                $data = [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Произошла ошибка",
                ];
                $result = Request::editMessageText($data);
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
