<?php
/*
* Generated by phinagata 0.1.14033101
* (C) dotAster Inc. All Rights Reserved.
* http://qiq.to/phinagata/
*/
require_once 'program/config.php';

class entryClass extends qiqFramework

{
  private $table = "login";
  public $login_data = array();
  public $display = array();
  function __construct()
  {
    $this->var = array(
      'mode',
      'seq',
      'flag',
      'authority',
      'login_name',
      'id',
      'password',
      'password_current',
      'password_new',
      'password_confirm',
      '_password',
      'offset',
      'ok',
      'cancel'
    );
    $this->import($this->var);
    $this->set_html_dir(HTMLDIR . '/password');
    $this->labelAuthority = array(
      0 => '管理権限なし',
      1 => '管理権限あり'
    );
    $this->openssl = new OpenSsl;
    $this->session = $_SESSION[AUTH_TAG_ADMIN];
    if (!$this->mode)
    {
      if ($this->session['authority'])
      {
        $this->mode = 'view';
      }
      else
      {
        $this->mode = 'form';
        $this->_seq = $this->session['seq'];
      }
    }
    if ($this->offset <= 0) $this->offset = 0;
    if ($this->limit <= 0) $this->limit = ADMIN_PASSWORD_VIEW_LIMIT;
    $this->query = array();
    $this->query_string = _http_build_query($this->query);
  }
  /* データ1件取得 */
  function get()
  {
    $this->db = new Database();
    $values = array(
      $this->_seq
    );
    $parameters = array(
      ':seq'
    );
    $types = array(
      PDO::PARAM_INT
    );
    $where = array(
      'seq = :seq'
    );
    $SQL = sqlSelect(array(
      'column' => array(
        "seq",
        "flag",
        "authority",
        "login_name",
        "id",
        "password",
        "priority"
      ) ,
      'from' => $this->table,
      'where' => $where
    ));
    try
    {
      $this->stmt = $this->db->prepare($SQL);
      foreach($values as $key => $value)
      {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $get_data = $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
      exit("GET PDO::errorInfo(): " . $e->getMessage());
    }
    $this->set($get_data, $this->var);
    if (defined('PASSWORD_TYPE') && PASSWORD_TYPE == 'ssl') {
      // opan ssl
      $this->_password = $this->openssl->decrypt($this->password);
    } elseif (PASSWORD_TYPE == 'nossl') {
    // nossl
      $this->_password = $this->session['_password'];
    }
    $this->db->close();
  }
  /* データ一覧取得 */
  function view()
  {
    $this->db = new Database();
    $values = array(
      // '20160513'
    );
    $parameters = array(
      // ':publish'
    );
    $types = array(
      // PDO::PARAM_STR
    );
    $where = array(
      // "publish = STR_TO_DATE(:publish, '%Y%m%d')"
    );
    $SQL = sqlSelect(array(
      'column' => "seq",
      'from' => $this->table,
      'where' => $where
    ));
    try
    {
      $this->stmt = $this->db->prepare($SQL);
      foreach($values as $key => $value)
      {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $this->count = $this->stmt->rowCount();
    }
    catch(PDOException $e)
    {
      exit("COUNT PDO::errorInfo(): " . $e->getMessage());
    }
    $SQL = sqlSelect(array(
      'column' => array(
        "seq",
        "flag",
        "authority",
        "login_name",
        "id",
        // "password",
        "priority"
      ) ,
      'from' => $this->table,
      'where' => $where,
      'order' => array(
        "priority DESC"
      ) ,
      'limit' => ':limit',
      'offset' => ':offset'
    ));
    try
    {
      $this->stmt = $this->db->prepare($SQL);
      foreach($values as $key => $value)
      {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
      $this->stmt->bindValue(':offset', $this->offset, PDO::PARAM_INT);
      $this->stmt->execute();
      $this->login_data = array_map('current', $this->stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC));
    }
    catch(PDOException $e)
    {
      exit("LIST PDO::errorInfo(): " . $e->getMessage());
    }
    $this->pager = new qiqPager(array(
      'count' => $this->count,
      'offset' => $this->offset,
      'limit' => $this->limit,
      'query' => $this->query
    ));
    $this->db->close();
  }
  function form()
  {
    if ($this->_seq) $this->get();
  }
  function prepare()
  {
  }
  function check()
  {
    $err = array();
    if (!$this->login_name)
    {
      $err[] = "ACCOUNT NAMEを入力してください";
    }
    if (!$this->id)
    {
      $err[] = "IDを入力してください";
    }
    if (!preg_match("/^[a-zA-Z0-9]+$/", $this->password))
    {
      $err[] = "PASSWORDは半角英数字のみで入力してください";
    }
    $error_span = array(
      'start' => '',
      'end' => ''
    );
    if ($err)
    {
      foreach($err as $name => $val)
      {
        $this->error[$name] = $error_span['start'] . $val . $error_span['end'];
      }
      return 0;
    }
    return 1;
  }
  function confirm()
  {
    if ($this->cancel)
    {
      $this->mode = 'form';
      $this->view();
      return;
    }
    $this->prepare();
    if (!$this->check())
    {
      $this->mode = 'form';
      return;
    }
    $this->_flag-= 0;
    $this->_authority-= 0;
  }
  function commit()
  {
    $this->mode = 'form';
    if ($this->cancel)
    {
      return;
    }
    $this->prepare();
    if (!$this->check())
    {
      return;
    }
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用
    $types = array(
      PDO::PARAM_INT,
      PDO::PARAM_STR, // login_name
      PDO::PARAM_STR
      // id
      // PDO::PARAM_STR  // password
    );
    $columns = array(
      'flag = :flag',
      'login_name = :login_name',
      'id = :id'
      // 'password = :password',
    );
    $values = array(
      1,
      $this->login_name,
      $this->id
      // hash(HASH_ALGO, $this->password),
    );
    $parameters = array(
      ':flag',
      ':login_name',
      ':id'
      // ':password',
    );
    $where = array(
      'seq = :seq'
    );
    if ($this->password)
    {
      $types[] = PDO::PARAM_STR;
      $columns[] = 'password = :password';
      if (defined('PASSWORD_TYPE') && PASSWORD_TYPE == 'ssl') {
        // open ssl
        $values[] = $this->openssl->encrypt($this->_password);
      } elseif (PASSWORD_TYPE == 'nossl') {
        // nossl
        $values[] = hash(HASH_ALGO, $this->password);
      }
      $parameters[] = ':password';
    }
    if ($this->seq)
    {
      // 更新
      try
      {
        $seq = $this->seq;
        $SQL = pdo_sql('UPDATE', $this->table, $columns, $where);
        // $SQL        = "UPDATE $this->table SET " . implode(", ", $columns) . " WHERE seq = :seq";
        $this->stmt = $this->db->prepare($SQL);
        foreach($values as $key => $value)
        {
          $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
        }
        $this->stmt->bindValue(':seq', $this->_seq, PDO::PARAM_INT);
        $this->stmt->execute();
        $this->db->commit(); // トランザクション用
      }
      catch(PDOException $e)
      {
        $this->db->rollBack(); // トランザクション用
        $this->error[] = "UPDATE PDO::errorInfo(): " . $e->getMessage();
        return;
      }
    }
    else
    {
      // 新規作成
      try
      {
        $types[] = PDO::PARAM_STR;
        $columns[] = 'issue = :issue';
        $values[] = date('Y/n/j G:i:s');
        $parameters[] = ':issue';
        $SQL = pdo_sql('INSERT INTO', $this->table, $columns);
        $this->stmt = $this->db->prepare($SQL);
        foreach($values as $key => $value)
        {
          $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
        }
        $this->stmt->execute();
        $seq = $this->db->lastInsertId();
        // priority追加
        $SQL = new_pdo_sql($this->table);
        $this->stmt = $this->db->prepare($SQL);
        $this->stmt->bindValue(':priority', $seq, PDO::PARAM_INT);
        $this->stmt->bindValue(':seq', $seq, PDO::PARAM_INT);
        $this->stmt->execute();
        $this->db->commit(); // トランザクション用
      }
      catch(PDOException $e)
      {
        $this->db->rollBack(); // トランザクション用
        $this->error[] = "INSERT INTO PDO::errorInfo(): " . $e->getMessage();
        return;
      }
    }
    if ($seq == $this->session['seq'])
    {
      $SQL = sqlSelect(array(
        'column' => '*',
        'from' => $this->table,
        'where' => "seq = :seq"
      ));
      try
      {
        $this->stmt = $this->db->prepare($SQL);
        $this->stmt->bindValue(':seq', $seq, PDO::PARAM_INT);
        $this->stmt->execute();
        $get_data = $this->stmt->fetch(PDO::FETCH_ASSOC);
      }
      catch(PDOException $e)
      {
        exit("GET PDO::errorInfo(): " . $e->getMessage());
      }
      $get_data['_password'] = $this->password;
      session_regenerate_id(true);
      $_SESSION[AUTH_TAG_ADMIN] = $get_data;
      $_SESSION[AUTH_TAG_ADMIN]['times'] = strtotime(TIME_LIMIT);
    }
    $this->db->close();
    if ($this->session['authority'] == 1)
    {
      header("Location: " . MYSELF);
    }
    else
    {
      $this->mode = 'thanks';
      $this->thanks();
    }
  }
  function thanks()
  {
  }
  function confirm_delete()
  {
    if ($this->_seq) $this->get();
  }
  /* データ削除 */
  function delete()
  {
    $this->mode = 'view';
    $this->view();
    if ($this->cancel)
    {
      return;
    }
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用
    try
    {
      $sql = "DELETE FROM $this->table where seq = :delete_seq";
      $this->stmt = $this->db->prepare($sql);
      $this->stmt->bindValue(':delete_seq', $this->_seq, PDO::PARAM_INT);
      $this->stmt->execute();
      $this->db->commit(); // トランザクション用
    }
    catch(PDOException $e)
    {
      $this->db->rollBack(); // トランザクション用
      exit("DELETE PDO::errorInfo(): " . $e->getMessage());
    }
    $this->db->close();
    $this->mode = 'view';
    $this->view();
  }
}
$_auth = new Authentication();
$_entryClass = new entryClass();
$_entryClass->main('UTF-8');
