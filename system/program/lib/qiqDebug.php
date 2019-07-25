<?php

if (!defined('PHP_VERSION_ID'))
{
  $version = explode('.', PHP_VERSION);
  define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if (PHP_VERSION_ID < 50207 && is_array($version))
{
  if (!defined('PHP_MAJOR_VERSION')) define('PHP_MAJOR_VERSION', $version[0]);
  if (!defined('PHP_MINOR_VERSION')) define('PHP_MINOR_VERSION', $version[1]);
  if (!defined('PHP_RELEASE_VERSION')) define('PHP_RELEASE_VERSION', $version[2]);
}

function fblog($param, $label = null)
{
  global $g_qiqDebug;
  if ($g_qiqDebug) FB::log($param, $label);
}

function fbprintf($label, $format)
{
  $arg = func_get_args();
  array_splice($arg, 0, 2); // array_shift() x 2
  fblog(vsprintf($format, $arg) , $label);
}

function fbHeader($param)
{
  fblog($param, 'Header');
  exit;
}

function fbenv()
{
  global $g_qiqDebug;
  if (!$g_qiqDebug) return;
  FB::group('Superglobals');
  if ($_GET) fblog($_GET, '$_GET');
  if ($_POST) fblog($_POST, '$_POST');
  if ($_SESSION) fblog($_SESSION, '$_SESSION');
  if ($_FILES) fblog($_FILES, '$_FILES');
  FB::groupEnd();
}

function qiqDebugErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
  if (!(error_reporting() & $errno))
  {
    return;
  }

  FB::group('ErrorHandler');
  fbprintf($errstr, "in %s(%d): %s", $errfile, $errline, $errstr);
  fblog($errcontext, 'context');
  FB::groupEnd();
  return true;
}

function fbinit($host_patterns)
{
  global $g_qiqDebug;
  $hosts = explode(',', $host_patterns);
  if (!is_array($hosts) || empty($hosts)) return;

  // if match development site
  $check = $_SERVER['REMOTE_ADDR'];
  if (preg_replace($hosts, $hosts, $check) != $check)
  {
    $g_qiqDebug = true;
    if (PHP_MAJOR_VERSION >= 5)
    {
      require_once 'FirePHPCore/fb.php';
    }
    else
    {
      require_once 'FirePHPCore/fb.php4';
    }
    register_shutdown_function("fbenv");
    set_error_handler('qiqDebugErrorHandler');
  }
}