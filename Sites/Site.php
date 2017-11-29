<?php
use \Curl\Curl;
use \DiDom\Document;

abstract class Site {

    protected $flats = [];
    protected $userAgent = 'Mozilla/5.0 (Windows NT x.y; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0';
    protected $parseUrl;
    protected $curl;
    protected $error = null;

    public function __construct()
    {
        $this->curl = new Curl();
        $this->curl->setUserAgent($this->userAgent);
    }

    abstract function get_flats();
    abstract protected function get_markup();

}
