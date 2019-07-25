<?php
/*
* qiqFrameworkForMailForm.php: rev.13120601
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/
class qiqFrameworkForMailForm extends qiqFramework

{
  function wakeup()
  {
    register_shutdown_function(array(
      $this,
      'shutdownHandle'
    ));
    ob_start();
  }

  function _escape($value, $form = 0)
  {
    if (is_array($value))
    {
      $value = array_map(array(
        'qiqFramework',
        '_escape'
      ) , $value);
    }
    else
    if (!is_object($value))
    {
      $value = htmlspecialchars($value, ENT_QUOTES, 'utf-8');
    }

    return $value;
  }

  function shutdownHandle()
  {
    $contents = ob_get_contents();
    @ob_end_clean();
    header('X-FRAME-OPTIONS: SAMEORIGIN');
    print trim($contents);
    while (@ob_end_flush());
    exit;
  }
}