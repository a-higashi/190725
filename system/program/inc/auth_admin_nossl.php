<?php
session_start();
echo __LINE__;

define('TIME_LIMIT', "2 hour");

class Authentication extends qiqFramework
{
  private $table     = 'login'; // DBのテーブル情報
  private $directory = '/system/'; //　ログアウト後の飛び先などの指定
  public $error      = array();
  public $memory     = '';
  public $login_name = '';
  public $id         = '';
  public $password   = '';
  public $login      = '';
  public $logout     = '';

  function __construct()
  {
    $err           = '';
    $this->clsvars = array(
      'memory',
      'login_name',
      'id',
      'password',
      'login',
      'logout'
    );
echo __LINE__;

    $this->import($this->clsvars);
    $this->mcrypt = new Mcrypt;
    if (isset($_COOKIE['id'])) {
      $this->_id = $_COOKIE['id'];
    }
    if ($this->logout)
      $this->logout();
    if ($this->login) {
      $id       = trim(mb_convert_kana($this->id, 'as'));
      $password = trim(mb_convert_kana($this->password, 'as'));
      if (!$id)
        $err[] = 'USER ACCOUNTを入力してください';
      if (!$password)
        $err[] = 'PASSWORDを入力してください';
      if ($err) {
        $this->error = '<ul><li>' . implode('</li><li>', $err) . '</li></ul>';
      } else {
        $values     = array(
          $id,
        );
        $parameters = array(
          ":id",
        );
        $types      = array(
          PDO::PARAM_STR,
        );
        $where      = array(
          "id       = :id",
        );
        $member     = $this->get_contact($values, $parameters, $types, $where);
/*
        echo $member['password'];
        echo $this->mcrypt->decrypt($member['password']). "<br>";
*/
        echo $this->mcrypt->encrypt($password);

        if (!$member) {
          $this->error = "<ul><li>USER ACCOUNTかPASSWORDが違います</li></ul>";
        } else if ($member['password'] != $this->mcrypt->encrypt($password)) {
          $this->error = "<ul><li>USER ACCOUNTかPASSWORDが違います</li></ul>";
        } else {
          $member['_password'] = $password;
          $member['times']     = strtotime(TIME_LIMIT);
          session_regenerate_id(true);
          $_SESSION[AUTH_TAG_ADMIN] = $member;
          if ($this->memory) {
            setcookie('id', $id);
            setcookie('timestamp', time());
          } else {
            setcookie('id', '', time() - 1);
            setcookie('timestamp', '', time() - 3600);
          }
          header("Location: ". $this->directory);
          return;
        }
      }
    } else {
      if (isset($_SESSION[AUTH_TAG_ADMIN]) && $_SESSION[AUTH_TAG_ADMIN]['times'] < time()) {
        $this->logout();
      } else {
        if (isset($_SESSION[AUTH_TAG_ADMIN]) && $_SESSION[AUTH_TAG_ADMIN]['seq']) {
          $values     = array(
            $_SESSION[AUTH_TAG_ADMIN]['seq'],
            $_SESSION[AUTH_TAG_ADMIN]['id'],
            $_SESSION[AUTH_TAG_ADMIN]['password']
          );
          $parameters = array(
            ":seq",
            ":id",
            ":password"
          );
          $types      = array(
            PDO::PARAM_INT,
            PDO::PARAM_STR,
            PDO::PARAM_STR
          );
          $where      = array(
            'seq      = :seq',
            'id       = :id',
            'password = :password'
          );
          $member     = $this->get_contact($values, $parameters, $types, $where);
          if ($member) {
            $_SESSION[AUTH_TAG_ADMIN]['times'] = strtotime(TIME_LIMIT);
            return;
          }
        }
      }
    }
    $this->prompt();
  }
  function get_contact($values, $parameters, $types, $where)
  {
    $this->db = new Database();
    try {
      $SQL        = sqlSelect(array(
        'column' => array(
          $this->table. ".*"
        ),
        'from' => array(
          $this->table
        ),
        'where' => $where
      ));
      $this->stmt = $this->db->prepare($SQL);
      foreach ($values as $key => $value) {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $login = $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      echo "CONNECT PDO::errorInfo(): " . $e->getMessage();
      return;
    }
    $this->db->close();
    return $login;
  }
  function logout()
  {
    $this->clearSession();
    setcookie('timestamp', '', time() - 3600);
    header('Location: '. $this->directory);
    exit;
  }
  function prompt()
  {
    unset($_SESSION[AUTH_TAG_ADMIN]);
    $this->mb_include(HTMLDIR . '/login.html');
    exit;
  }
  function clearSession()
  {
    unset($_SESSION[AUTH_TAG_ADMIN]);
    setcookie(session_name(), '', time() - 3600, "/");
  }
}
function get_login_contact($column)
{
  return $_SESSION[AUTH_TAG_ADMIN][$column];
}
function is_login()
{
  return get_login_contact('seq');
}
