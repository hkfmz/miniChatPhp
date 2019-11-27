<?php

include_once(__DIR__.'/../security/setting.php');

abstract class DbConnect
{
  // cant make a direct object for abstract
    public $link;
    function __construct()
    {
        $this->link=include(__DIR__.'/../security/config.php');
        return $this->link;
    }
}

/*

By Hegel Motokoua

*/