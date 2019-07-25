<?php
/*
* pdo_db.php: var1.3
*
*/
// PHPのバージョンを調べる
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
class Database extends PDO

{
  protected $transactionCounter = 0;
  public $db = null;

  public function __construct()

  {
    $this->db = $this->pdo_connect();
    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  public function __destruct()

  {
    $this->close();
  }
  // connect now confirmation
  public function is_connected()

  {
    return ((bool)($this->db instanceof PDO));
  }
  // connect out
  public function close()

  {
    $this->db = null;
  }
  // PDO 接続用
  public static function pdo_connect()

  {
    $dbhost = '';
    $charset = '';
    $dsn = '';
    $dbport = '';
    $dbname = '';
    if (DBTYPE == 'mysql')
    {
      if (defined('DBNAME') && DBNAME)
      {
        $dbname = ':dbname=' . DBNAME;
      }
      else
      {
        exit('Error: No dbname');
      }
      if (defined('DBHOST') && DBHOST)
      {
        $dbhost = ';host=' . DBHOST;
      }
      else
      {
        $dbhost = ';host=localhost';
      }
      if (defined('DBPORT') && DBPORT)
      {
        $dbport = ';port=' . DBPORT;
      }
      if (PHP_VERSION_ID > 50329)
      {
        $charset = ';charset=utf8';
      }
      $dsn = DBTYPE . $dbname . $dbhost . $dbport . $charset;
    }
    elseif (DBTYPE == 'pgsql')
    {
      if (defined('DBNAME') && DBNAME)
      {
        $dbname = ':dbname=' . DBNAME;
      }
      else
      {
        exit('Error: No dbname');
      }
      if (defined('DBHOST') && DBHOST)
      {
        $dbhost = ' host=' . DBHOST;
      }
      else
      {
        $dbhost = ';host=localhost';
      }
      if (defined('DBPORT') && DBPORT)
      {
        $dbport = ' port=' . DBPORT;
      }
      if (PHP_VERSION_ID > 50329)
      {
        $charset = ' charset=utf8';
      }
      $dsn = DBTYPE . $dbname . $dbhost . $dbport . $charset;
    }
    elseif (DBTYPE == 'sqlite')
    {
      if (defined('DBNAME') && DBNAME)
      {
        $dsn = DBTYPE . ':' . DBNAME;
      }
      else
      {
        exit('Error: No dbname');
      }
    }
    $options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    );
    $db = new PDO($dsn, DBUSER, DBPASS, $options);
    return $db;
  }
  public function prepare($sql, $driverOptions = array(
))
  {
    return $this->db->prepare($sql, $driverOptions);
  }
  public function last_insert_id($table)

  {
    $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
    $lastId = $stmt->fetch(PDO::FETCH_NUM);
    $lastId = $lastId[0];
    return $lastId;
  }
  public function lastInsertId($seqname = NULL)

  {
    return $this->db->lastInsertId();
  }
  public function beginTransaction()

  {
    if (!$this->transactionCounter++) return $this->db->beginTransaction();
    return $this->transactionCounter >= 0;
  }
  public function commit()

  {
    if (!--$this->transactionCounter) return $this->db->commit();
    return $this->transactionCounter >= 0;
  }
  public function rollback()

  {
    if ($this->transactionCounter >= 0)
    {
      $this->transactionCounter = 0;
      return $this->db->rollback();
    }
    $this->transactionCounter = 0;
    return false;
  }

  function param($value)
  {
    return "%{$value}%";
  }

  function param_no($value)
  {
    return "%'".$value."'%";
  }

}
// プレースホルダのタイプを選択
function pdo_type($type)
{
  if ($type === 'integer')
  {
    return PDO::PARAM_INT;
  }
  else if ($type === 'text')
  {
    return PDO::PARAM_STR;
  }
}
// sql構文簡略化
function pdo_sql($syntax, $table, $columns, $where = null)
{
  $sql = sprintf("%s %s SET ", $syntax, $table);
  $sql.= implode(', ', $columns);
  if ($where)
  {
    $sql.= " WHERE " . implode(' AND ', $where);
  }
  return $sql;
}
// sql構文簡略化
function new_pdo_sql($table)
{
  $sql = "UPDATE " . $table . " SET priority =:priority  WHERE seq = :seq";
  return $sql;
}

