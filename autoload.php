<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'parser';

function loadFromCurl($name)
{
    global $docRoot;
    $name = str_replace("\\", "/", $name);

    $filePath =  $docRoot. DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromDiDom($name)
{
    global $docRoot;
    $name = str_replace("\\", "/", $name);

    $filePath =  $docRoot. DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromSites($name)
{
    global $docRoot;

    $filePath =  $docRoot. DIRECTORY_SEPARATOR . 'Sites' . DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

spl_autoload_register('loadFromCurl');
spl_autoload_register('loadFromDiDom');
spl_autoload_register('loadFromSites');
