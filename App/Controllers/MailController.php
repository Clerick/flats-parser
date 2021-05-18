<?php namespace App\Controllers;

use App\Model\Mail;

class MailController
{
    private static $mail;

    public static function sendMail(array $updates)
    {
        // check if updates is empty
        $empty_counter = 0;
        foreach ($updates as $site) {
            if (empty($site)) {
                $empty_counter++;
            }
        }

        // if updates not empty send mail
        if ($empty_counter != count($updates)) {
            self::$mail = new Mail($updates);
            mail(self::$mail->to, self::$mail->subject, self::$mail->message);
        }
    }
}
