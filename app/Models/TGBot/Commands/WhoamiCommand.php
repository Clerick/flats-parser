<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\PhotoSize;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Request;

/**
 * User "/whoami" command
 *
 * Simple command that returns info about the current user.
 */
class WhoamiCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'whoami';

    /**
     * @var string
     */
    protected $description = 'Show your id, name and username';

    /**
     * @var string
     */
    protected $usage = '/whoami';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $from = $message->getFrom();
        $user_id = $from->getId();
        $chat_id = $message->getChat()->getId();
        $message = "Your Id: " . $user_id . PHP_EOL
            . "Name: " . $from->getFirstName() . " " . $from->getLastName() . PHP_EOL
            . "Username: " . $from->getUsername();

        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
        ];
        return Request::sendMessage($data);
    }

}
