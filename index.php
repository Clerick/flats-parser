<?php
require_once 'autoload.php';

function var_dump_pre($mixed = null)
{
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
}

$updates = UpdatesController::getUpdates();

// $to = '13_spirit@mail.ru';
// $subject = 'New flats';

// Message
// $message = "";
// foreach ($updates as $flat) {
//     $message .= $flat->getPrice() . "\r\n";
//     $message .= $flat->getAddress() . "\r\n";
//     $message .= $flat->getLink() . "\r\n";
// }

// To send HTML mail, the Content-type header must be set
//$headers[] = 'MIME-Version: 1.0';
//$headers[] = 'Content-type: text/plain; charset=iso-8859-1';
//
//// Additional headers
// $headers[] = 'From: Parser crazyvezdehod.esy.es';

// Mail it
//if (mail($to, $subject, $message, implode("\r\n", $headers)))
//    echo 'послал';


//if (mail($to, $subject, $message))
//{
//    echo 'послал';
//}

?>
