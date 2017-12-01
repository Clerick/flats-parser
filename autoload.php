<?php
$models_root = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'Models';
$document_root = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'parser';

function loadFromCurl($name)
{
    global $models_root;
    $name = str_replace("\\", "/", $name);

    $filePath =  $models_root. DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromDiDom($name)
{
    global $models_root;
    $name = str_replace("\\", "/", $name);

    $filePath =  $models_root. DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromSites($name)
{
    global $models_root;

    $filePath =  $models_root. DIRECTORY_SEPARATOR . 'Sites' . DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromDB($name)
{
    global $models_root;

    $filePath =  $models_root. DIRECTORY_SEPARATOR . 'DB' . DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromControllers($name)
{
    global $document_root;

    $filePath =  $document_root. DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

function loadFromViews($name)
{
    global $document_root;

    $filePath =  $document_root. DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $name . '.php';

    if(file_exists($filePath))
    {
        require_once $filePath;
    }
}

spl_autoload_register('loadFromCurl');
spl_autoload_register('loadFromDiDom');
spl_autoload_register('loadFromSites');
spl_autoload_register('loadFromDB');
spl_autoload_register('loadFromControllers');
spl_autoload_register('loadFromViews');

