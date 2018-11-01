<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use App\Utils\SiteUtil;
use App\Models\DB\DBInterface;
use App\Models\DB\SQLDB;
use App\Models\DB\DatabaseConfiguration;
use App\Models\TGBot\MessageBuilder;

/**
 * User "/whoami" command
 *
 * Simple command that returns info about the current user.
 */
class GetCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'get';

    /**
     * @var string
     */
    protected $description = 'get last time parsed flats from db';

    /**
     * @var string
     */
    protected $usage = '/get';

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

        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action' => 'typing',
        ]);

        $this->initialize();

        $site_class_names = SiteUtil::getSiteClassNames();

        foreach ($site_class_names as $site_class_name)
        {
            $flats = $this->db->getLastUpdate($site_class_name);

            $text = MessageBuilder::build(SiteUtil::getSiteAlias($site_class_name), $flats);

            $data = [
                'chat_id' => $chat_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' =>true,
            ];

            $result = Request::sendMessage($data);
        }

        return $result;
    }

}
