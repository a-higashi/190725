<?php
/*
* Generated by phinagata 0.1.14033101
* (C) dotAster Inc. All Rights Reserved.
* http://qiq.to/phinagata/
*/
require_once 'program/config.php';

class indexClass extends qiqFramework

{
  function __construct()
  {
    $this->var = array(
      'mode',
    );
    $this->set_html_dir(HTMLDIR . '/');
    if (!$this->mode) $this->mode = 'index';
  }
}
$_auth = new Authentication();
$_indexClass = new indexClass();
$_indexClass->main('UTF-8');