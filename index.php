<?php

require __DIR__ . '/vendor/autoload.php';
use App\Controllers\MailController;
use App\Controllers\UpdatesController;

$updates = UpdatesController::getUpdates();
// MailController::sendMail($updates);
echo "parsing was successful";
var_dump($updates);

function var_dump_pre($mixed = null)
{
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
}
