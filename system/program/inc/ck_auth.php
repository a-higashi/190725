<?php
session_start();
class Authentication extends qiqFramework
{
  var $error = array();
  var $memory;
  var $login_name;
  var $id;
  var $password;
  function __construct()
  {
    $this->clsvars = array(
      'memory',
      'login_name',
      'id',
      'password'
    );
    $this->import($this->clsvars);
    if (isset($_SESSION[AUTH_TAG_ADMIN]['seq']))
    {
      $values = array(
        $_SESSION[AUTH_TAG_ADMIN]['seq'],
        $_SESSION[AUTH_TAG_ADMIN]['id'],
        $_SESSION[AUTH_TAG_ADMIN]['password']
      );
      $parameters = array(
        ":seq",
        ":id",
        ":password"
      );
      $types = array(
        PDO::PARAM_INT,
        PDO::PARAM_STR,
        PDO::PARAM_STR
      );
      $where = array(
        'seq = :seq',
        'id = :id',
        'password = :password'
      );
      $member = $this->get_contact($values, $parameters, $types, $where);
      if ($member) return;
    }
    else
    {
      header('HTTP', true, 404);
      exit;
    }
  }
  function get_contact($values, $parameters, $types, $where)
  {
    $this->db = new Database();
    try
    {
      $SQL = sqlSelect(array(
        'column' => array(
          "login.*"
        ) ,
        'from' => array(
          'login'
        ) ,
        'where' => $where
      ));
      $this->stmt = $this->db->prepare($SQL);
      foreach($values as $key => $value)
      {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $login = $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
      echo "CONNECT PDO::errorInfo(): " . $e->getMessage();
      return;
    }
    $this->db->close();
    return $login;
  }
}