<?php
define('ENV_PRODUCTION', FALSE); // 本番環境かどうか
// .htaccessが使えない場合
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
ob_start('mb_output_handler');

// ネットワークタイムゾーン設定
if (function_exists('date_default_timezone_set'))
{
  date_default_timezone_set('Asia/Tokyo');
}
else
{
  ini_set('date.timezone', 'Asia/Tokyo');
}

// //////////////////////////////////////////////////////////////////////
/* 基本情報 */
if (defined('ENV_PRODUCTION') && ENV_PRODUCTION)
{
  define('SITEURL', ''); // %%tag_SITEURL%%
}
else
{
  define('SITEURL', ''); // %%tag_SITEURL%%
}

// authのパスワードタイプ
define('PASSWORD_TYPE', 'ssl');

// auth定数
define('AUTH_TAG_ADMIN', 'auth_admin');

if (defined('PASSWORD_TYPE') && PASSWORD_TYPE == 'ssl')
{
//  パスワード暗号化　OPENSSL
  define('PASS_KEY', 'Wa2yCAg4');
  define('IV_KEY', 'Ki2c5uS9');
}
else
{
  define('IV_KEY', '2XDiYHFXCr2SuaGU');
  define('SECRET', 'qTtTwfWJTV8ckBq6');
}

//  ハッシュ
define('HASH_ALGO', 'sha256');

// //////////////////////////////////////////////////////////////////////
/* 管理画面情報 */
define('SITENAME', ' '); // %%tag_SITENAME%%
define('COPYRIGHT', 'Copyright © ' . date('Y') . ' ALL RIGHTS RESERVED.'); // %%tag_COPYRIGHT%%
/* メール関連 */
define('FROMNAME',    '');    // %%tag_FROMNAME%%
if (defined('ENV_PRODUCTION') && ENV_PRODUCTION)
{
  define('MAILFROM',    '');  // %%tag_MAILFROM%%
  define('ADMINEMAIL',  '');  // %%tag_ADMINEMAIL%%
  define('RETURNMAIL',  '');  // %%tag_ADMINEMAIL%%
}
else
{
  define('MAILFROM',    ''); // %%tag_MAILFROM%%
  define('ADMINEMAIL',  ''); // %%tag_ADMINEMAIL%%
  define('RETURNMAIL',  '');  // %%tag_ADMINEMAIL%%
  // define('BCC',  'higashi@queserser.co.jp');  // %%tag_ADMINEMAIL%%
}
  define('SUBJECT_CONTACT_ADMIN', 'お問い合わせがありました'); // %%tag_DBUSER%%
  define('SUBJECT_CONTACT', 'お問い合わせありがとうございます'); // %%tag_DBPASS%%

/* データベース関連 */
define('DBTYPE', 'mysql'); // %%tag_DBTYPE%%
if (defined('ENV_PRODUCTION') && ENV_PRODUCTION)
{
  define('DBHOST', ''); // %%tag_DBHOST%%
  define('DBUSER', ''); // %%tag_DBUSER%%
  define('DBPASS', ''); // %%tag_DBPASS%%
  define('DBNAME', ''); // %%tag_DBNAME%%
  // define('DBPORT', '3306'); // %%tag_DBPASS%%
}
else
{
  if (DIRECTORY_SEPARATOR == '\\')
  {
    define('DBHOST', 'localhost'); // %%tag_DBHOST%%
    define('DBUSER', 'root'); // %%tag_DBUSER%%
    define('DBPASS', '1234'); // %%tag_DBPASS%%
    define('DBNAME', ''); // %%tag_DBNAME%%
    define('DBPORT', ''); // %%tag_DBPASS%%
  }
  else
  {
    define('DBHOST', ''); // %%tag_DBHOST%%
    define('DBUSER', ''); // %%tag_DBUSER%%
    define('DBPASS', ''); // %%tag_DBPASS%%
    define('DBNAME', ''); // %%tag_DBNAME%%
  }
}
define('qDB_CLIENTCHARSET', 'utf8');
/* 表示件数 */
define('PASSWORD_VIEW_LIMIT', 10); // 表画面新着情報TOP一覧
define('ADMIN_PASSWORD_VIEW_LIMIT', 20); // 表画面新着情報一覧
define('NEWS_TOP_LIMIT', 5); // 表画面新着情報TOP一覧
define('NEWS_VIEW_LIMIT', 10); // 表画面新着情報一覧
define('NEWS_REC_TOP_LIMIT', 5); // 表画面新着情報TOP一覧
define('NEWS_REC_VIEW_LIMIT', 10); // 表画面新着情報一覧
define('NEWS_PICKUP_LIMIT', 1); // 表画面新着情報一覧
define('ADMIN_NEWS_VIEW_LIMIT', 20); // 管理画面新着情報一覧
define('KEYWORD_VIEW_LIMIT', 50); // 表画面新着情報一覧
// テスト環境IPアドレス(デバッグ表示)
define('qiqDebug_REMOTE_ADDR', '/^118.243.35.xxx$/,/^192.168.1./,/^127.0.0/');
// //////////////////////////////////////////////////////////////////////
/* date */
define('SYSTEM_START_YEAR', 2015);
define('SEL_DEFAULT', '▼選択');
define('SEL_YEAR_DEFAULT', '----');
define('SEL_MONTH_DEFAULT', '--');
define('SEL_DAY_DEFAULT', '--');
define('SEL_HOUR_DEFAULT', '--');
define('SEL_MINUTE_DEFAULT', '--');
define('SEL_SECOND_DEFAULT', '--');
/* dir */
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('BASEDIR', dirname(__FILE__)); // %%tag_BASEDIR%%
define('SCRIPT_DIR', dirname($_SERVER["SCRIPT_NAME"])); // %%tag_SCRIPTDIR%%
// //////////////////////////////////////////////////////////////////////
/* 基本設定 */

define('ENABLE_DEBUG', true);
define('DEBUG', false);
if (defined('ENV_PRODUCTION') && ENV_PRODUCTION)
{
  error_reporting();
}
else
{
  error_reporting();
}

if (ENABLE_DEBUG && DEBUG) ini_set('display_errors', 'On');
define('HTMLDIR', BASEDIR . '/html');
define('LIBDIR', BASEDIR . '/lib');
define('INCDIR', BASEDIR . '/inc');
define('MAILDIR', BASEDIR . '/mail');
define('DATADIR', BASEDIR . '/data');
define('TEMPLATE_DIR', DOCUMENT_ROOT . '/template');
define('MYSELF', htmlspecialchars($_SERVER['PHP_SELF']));
$search = array(
  'index.php'
);
$replace = array(
  ''
);
$url = str_replace($search, $replace, MYSELF);
define('MY_SELF', $url);
define('DSN', DBTYPE . '://' . DBUSER . ':' . DBPASS . '@' . DBHOST . '/' . DBNAME);
$inc = array(
  INCDIR,
  LIBDIR
);

array_push($inc, ini_get('include_path'));
if (DIRECTORY_SEPARATOR == '\\')
{
  ini_set('include_path', join(';', $inc));
}
else
{
  ini_set('include_path', join(':', $inc));
}
session_save_path(DATADIR . '/sess');
session_cache_limiter('');
require_once 'qiqDebug.php';
require_once 'qiqFramework.php';
require_once 'qiqFrameworkForMailForm.php';
require_once 'qiqForm.php';
require_once 'qiqCheckboxes.php';
require_once 'qiqPager.php';
require_once 'PDO_DB.php';
require_once 'qiqSQL.php';
require_once 'PDO_reorder.php';
require_once 'qiqOpneSsl.php';
require_once 'qiqMcrypt.php';
require_once 'functions.php';
if (strstr(SCRIPT_DIR, '/system/js'))
{
  require_once 'ck_auth.php'; // エディタ用
  require_once 'ck.php';
}
elseif (strstr(SCRIPT_DIR, '/system'))
{
  if (defined('PASSWORD_TYPE') && PASSWORD_TYPE == 'ssl')
  {
    require_once 'auth_admin.php'; // 管理画面用
  }
  elseif (PASSWORD_TYPE == 'nossl')
  {
    require_once 'auth_admin_nossl.php'; // 管理画面用
  }
}
fbinit(qiqDebug_REMOTE_ADDR);
/* 正規表現 */
define('ALPHABET_CHECK', '/^[a-zA-Z]+$/');
define('PHONE_CHECK', '/^[0-9]{11}$|^[0-9]{10}$|^\d{1,4}-\d{1,4}-\d{1,5}$/');
define('PHONE_3_CHECK', '/^\d{2,5}$/');
define('MAIL_CHECK', '/^[-\w\._+]+@[-\w]+\.[-\w\.]+$/');
define('KATAKANA_CHECK', '/^([　 \t\r\n]|[ァ-ヶー]|[ー])+$/u');
define('HIRAKANA_CHECK', '/^([　 \t\r\n]|[ぁ-んー]|[ー])+$/u');
define('ZIP_CHECK', '/(^\d{3}\-\d{4}$)|(^\d{7}$)/');
define('ZIP_1_CHECK', '/^\d{3}$/');
define('ZIP_2_CHECK', '/^\d{4}$/');
define('NUMBER_CHECK', '/^[0-9]+$/');
define('URL_CHECK', '/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/');
