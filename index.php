<?php
require_once 'autoload.php';
use \Curl\Curl;
use \DiDom\Document;

$kvartirantSite = new KvartirantSite();

$kvartirantSite->get_flats();
