<?php
require_once 'program/config.php';
require_once 'master.php';
require_once 'qiqImage.php';
require_once 'ck.php';

class newsClass extends qiqFramework
{
  /* DBのテーブル情報 */
  private $table       = 'interview_detail';
  /* view一覧 */
  // public $news      = array();
  /* 一覧表示用 */
  public $display      = array();
  /* コンテンツ名 */
  public $item_subject = 'インタビュー セクション';
  public $enter_mode   = 0; // 0 だとデフォルトが<p>、1だと<br>
  public $ckListCss    = array();
  public $ckclass      = '';
  public $ckid         = '';

  public function __construct()
  {
    $this->var        = array(
      'mode',
      'seq',
      'flag',
      'publish',
      'publish_year',
      'publish_month',
      'publish_day',
      'parents',
      'category',
      'cat',
      'title',
      // 'image_tmp',
      // 'image_delete',
      'subimg_tmp',
      'subimg_delete',
      'body',
      'offset',
      'limit',
      'priority',
      'ok',
      'cancel',
      'update',
      'fixorder',
      'order',
      'fix'
    );
    $this->labelDelete = array(
      1 => '画像を削除'
    );
    $this->import($this->var);
    $this->set_html_dir(HTMLDIR . '/interview_detail');
    $this->subimg       = new qiqImage(array(
      'name'    => "subimg",
      'type'    => 'jpg',
      'tmp_id'  => $this->subimg_tmp,
      'dir'     => '../upload/interview',
      'format'  => 'subimg%08d.jpg',
      'resize'  => 7,
      'width'   => 590,
      'height'  => 400,
      'tmp_dir' => './tmp'
    ));
    $this->subimg->format($this->seq);
    // ckeditar CSS
    // $this->ckListCss   = array(
    //   "/system/template/common/css/editor.css"
    // );
    $this->labelCheck  = array(
      1 => ''
    );
    $this->labelFlag   = array(
      0 => '非公開',
      1 => '公開'
    );
    $this->labelCategory = get_label('interview', array(
      'column'  => 'title',
      'where'   => 'flag = 1',
      'order'   => "priority DESC",
    ));
    // if ($this->offset <= 0) $this->offset = 0;
    // if ($this->limit <= 0) $this->limit = ADMIN_NEWS_VIEW_LIMIT;
    if (!$this->mode) $this->mode = 'view';
    $this->query        = array('parents'=>$this->_parents);
    $this->query_string = _http_build_query($this->query);
  }
  /* データ1件取得 */
  function get()
  {
    $this->db   = new Database();

    $values     = array(
      $this->seq
    );
    $parameters = array(
      ':seq'
    );
    $types      = array(
      PDO::PARAM_INT
    );
    $where      = array(
      'seq = :seq'
    );
    $SQL        = sqlSelect(array(
      'column' => array(
        "seq",
        "flag",
        // db_datetime('publish'),
        "parents",
        "title",
        "body",
        "priority"
      ),
      'from' => $this->table,
      'where' => $where
    ));
    try {
      $this->stmt = $this->db->prepare($SQL);
      foreach ($values as $key => $value) {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $get_data = $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      print("GET PDO::errorInfo(): " . $e->getMessage());
    }
    $this->set($get_data, $this->var);
    /*
    if ($this->_publish) {
      $this->_publish = $this->publish  = date('Y/m/d', $this->_publish);
    }
    */
    $this->subimg->format($this->seq);
    $this->db->close();
  }
  /* データ一覧取得 */
  function view()
  {
    $this->db   = new Database();

    $values     = array(
      // '20160513'
    );
    $parameters = array(
      // ':publish'
    );
    $types      = array(
      // PDO::PARAM_STR
    );
    $where      = array(
      // "publish = STR_TO_DATE(:publish, '%Y%m%d')"
    );
    if ($this->_parents) {
      $values[] = $this->_parents;
      $parameters[] = ':parents';
      $types[] = PDO::PARAM_INT;
      $where[] = "(parents = :parents)";
    }
    $SQL        = sqlSelect(array(
      'column' => "seq",
      'from' => $this->table,
      'where' => $where
    ));
    try {
      $this->stmt = $this->db->prepare($SQL);
      foreach ($values as $key => $value) {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->execute();
      $this->count = $this->stmt->rowCount();
    }
    catch (PDOException $e) {
      print("COUNT PDO::errorInfo(): " . $e->getMessage());
    }
    $SQL = sqlSelect(array(
      'column' => array(
        "seq",
        "flag",
        // db_datetime('publish'),
        "parents",
        "title",
        "body",
        "priority"
      ),
      'from' => $this->table,
      'where' => $where,
      'order' => array(
        // "publish DESC",
        "priority DESC"
      ),
      // 'limit' => ':limit',
      // 'offset' => ':offset'
    ));
    try {
      $this->stmt = $this->db->prepare($SQL);
      foreach ($values as $key => $value) {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      // $this->stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
      // $this->stmt->bindValue(':offset', $this->offset, PDO::PARAM_INT);
      $this->stmt->execute();
      $this->display = array_map('current', $this->stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC));
    }
    catch (PDOException $e) {
      print("LIST PDO::errorInfo(): " . $e->getMessage());
    }
    /*
    // ページング
    $this->pager = new qiqPager(array(
      'count' => $this->count,
      'offset' => $this->offset,
      'limit' => $this->limit,
      'query' => $this->query
    ));
    */
    $this->db->close();
  }
  /* フラグ更新 */
  function update()
  {
    $order    = array();
    $category = array();
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用
    if ($this->fixorder) {
      $priority = array();
      $order = explode(',', $this->order);
      $priority = array_reverse($order);
      try {
        foreach ($priority AS $index => $seq) {
          $n = $index + 1;
          try {
            $values = array(
              $n
            );
            $parameters = array(
              ':priority'
            );
            $types = array(
              PDO::PARAM_INT
            );
            $columns = array(
              "priority = :priority"
            );
            $where = array(
              "seq =:seq"
            );
            $SQL = pdo_sql('UPDATE', $this->table, $columns, $where);
            $this->stmt = $this->db->prepare($SQL);
            foreach ($values as $key => $value) {
              $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
            }
            $this->stmt->bindValue(':seq', $seq, PDO::PARAM_INT);
            $this->stmt->execute();
          }
          catch (PDOException $e) {
            print("UPDATE ERROR ORDER SEQ $seq PDO::errorInfo(): " . $e->getMessage());
          }
        }
        $this->db->commit(); // トランザクション用
      }
      catch (PDOException $e) {
        $this->db->rollBack(); // トランザクション用
        print("UPDATE ERROR ORDER TRANSACTION PDO::errorInfo(): " . $e->getMessage());
      }
    }
    if ($this->update) {
      try {
        foreach ($this->seq AS $seq => $dummy) {
          try {
            $flag       = (isset($this->flag[$seq])) ? 1 : 0;
            $values     = array(
              $flag,
            );
            $parameters = array(
              ':flag',
            );
            $types      = array(
              PDO::PARAM_INT,
            );
            $columns    = array(
              "flag = :flag",
            );
            $where      = array(
              "seq =:seq"
            );
            $SQL        = pdo_sql('UPDATE', $this->table, $columns, $where);
            $this->stmt = $this->db->prepare($SQL);
            foreach ($values as $key => $value) {
              $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
            }
            $this->stmt->bindValue(':seq', $seq, PDO::PARAM_INT);
            $this->stmt->execute();
          }
          catch (PDOException $e) {
            print("UPDATE FLAG ERROR SEQ $seq PDO::errorInfo(): " . $e->getMessage());
          }
        }
        $this->db->commit(); // トランザクション用
      }
      catch (PDOException $e) {
        $this->db->rollBack(); // トランザクション用
        print("UPDATE FLAG ERROR TRANSACTION PDO::errorInfo(): " . $e->getMessage());
      }
    }
    $this->db->close();
    $this->mode = 'view';
    $this->view();
  }
  function form()
  {
    if ($this->seq) $this->get();
    /*
    if (!$this->publish) {
      $this->publish_year  = date('Y');
      $this->publish_month = date('m');
      $this->publish_day   = date('d');
      $this->publish       = sprintf('%04d/%02d/%02d', $this->publish_year, $this->publish_month, $this->publish_day);
    }
    */
  }
  function prepare()
  {
  }
  function check()
  {
    $err = array();
    /*
    if (!$this->publish) {
      $err[] = "掲載日を入力してください";
    } elseif (!validate_date($this->publish)) {
      $err[] = "掲載日を正しく入力してください";
    }
    if (!$this->category) {
      $err[] = "カテゴリを選択してください";
    }
    if (!is_file($this->image->path()) && !is_file($this->image->tmp_path())) {
      $err[] = "画像を入力してください";
    }
    */
    if (!$this->title) {
      $err[] = "見出しを入力してください";
    }
    if (!$this->body) {
      $err[] = "本文を入力してください";
    }
    $error_span = array(
      'start' => '',
      'end' => ''
    );
    if ($err) {
      foreach ($err as $name => $val) {
        $this->error[$name] = $error_span['start'] . $val . $error_span['end'];
      }
      return 0;
    }
    return 1;
  }
  function confirm()
  {
    $this->subimg->save_tmp();
    if ($this->cancel) {
      $this->subimg->purge(true);
      $this->mode = 'view';
      $this->view();
      return;
    }
    $this->prepare();
    if (!$this->check()) {
      $this->mode = 'form';
      return;
    }
    $this->_flag = $this->_flag - 0;
  }
  function commit()
  {
    $this->mode = 'form';
    if ($this->cancel) {
      return;
    }
    $this->prepare();
    if (!$this->check()) {
      return;
    }
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用

    $types      = array(
      PDO::PARAM_INT,
      // PDO::PARAM_INT,
      PDO::PARAM_INT,
      PDO::PARAM_STR,
      PDO::PARAM_STR
    );
    $columns    = array(
      'flag = :flag',
      // 'publish = :publish',
      'parents = :parents',
      'title = :title',
      'body = :body'
    );
    $values     = array(
      $this->flag - 0,
      // $this->publish,
      $this->parents,
      $this->title,
      $this->body
    );
    $parameters = array(
      ':flag',
      // ':publish',
      ':parents',
      ':title',
      ':body'
    );
    $where      = array(
      'seq = :seq'
    );
    if ($this->seq) {
      // 更新
      try {
        $seq        = $this->seq;
        $SQL        = pdo_sql('UPDATE', $this->table, $columns, $where);
        // $SQL        = "UPDATE $this->table SET " . implode(", ", $columns) . " WHERE seq = :seq";
        $this->stmt = $this->db->prepare($SQL);
        foreach ($values as $key => $value) {
          $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
        }
        $this->stmt->bindValue(':seq', $this->seq, PDO::PARAM_INT);
        $this->stmt->execute();
        $this->db->commit(); // トランザクション用
      }
      catch (PDOException $e) {
        $this->db->rollBack(); // トランザクション用
        $this->error[] = "UPDATE PDO::errorInfo(): " . $e->getMessage();
        return;
      }
    } else {
      // 新規作成
      try {
        $types[]      = PDO::PARAM_STR;
        $columns[]    = 'issue = :issue';
        $values[]     = date('Y/n/j G:i:s');
        $parameters[] = ':issue';
        $SQL          = pdo_sql('INSERT INTO', $this->table, $columns);
        // $SQL          = "INSERT INTO $this->table SET " . implode(", ", $columns);
        $this->stmt   = $this->db->prepare($SQL);
        foreach ($values as $key => $value) {
          $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
        }
        $this->stmt->execute();
        $seq        = $this->db->lastInsertId();
        // priority追加
        $SQL        = new_pdo_sql($this->table);
        // $SQL        = "UPDATE $this->table SET priority =:priority  WHERE seq = :seq";
        $this->stmt = $this->db->prepare($SQL);
        $this->stmt->bindValue(':priority', $seq, PDO::PARAM_INT);
        $this->stmt->bindValue(':seq', $seq, PDO::PARAM_INT);
        $this->stmt->execute();
        $this->db->commit(); // トランザクション用
      }
      catch (PDOException $e) {
        $this->db->rollBack(); // トランザクション用
        $this->error[] = "INSERT INTO PDO::errorInfo(): " . $e->getMessage();
        return;
      }
    }
    $this->db->close();
    $this->subimg->format($seq);
    if ($this->subimg_delete)
      $this->subimg->delete();
    $this->subimg->save($seq);
    if($this->parents){
      header("Location: " . MYSELF . "?parents=" . $this->parents);
    } else {
      header("Location: " . MYSELF);
    }
    // $this->mode = 'view';
    // $this->view();
  }
  function confirm_delete()
  {
    if ($this->seq) $this->get();
  }
  /* データ削除 */
  function delete()
  {
    $this->get();
    $this->mode = 'view';
    $this->view();
    if ($this->cancel) {
      return;
    }
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用
    try {
      $sql        = "DELETE FROM $this->table where seq = :seq";
      $this->stmt = $this->db->prepare($sql);
      $this->stmt->bindValue(':seq', $this->seq, PDO::PARAM_INT);
      $this->stmt->execute();
      $this->db->commit(); // トランザクション用
    }
    catch (PDOException $e) {
      $this->db->rollBack(); // トランザクション用
      print("DELETE PDO::errorInfo(): " . $e->getMessage());
    }

    $this->db->close();
    $this->subimg->format($this->seq);
    $this->subimg->delete();
    if($this->parents){
      header("Location: " . MYSELF . "?parents=" . $this->parents);
    } else {
      header("Location: " . MYSELF);
    }
    // $this->mode = 'view';
    // $this->view();
  }
/*  public function top()
  {
  }
  public function up()
  {
    $this->get();
    priority(-1, $this->seq, $this->priority, $this->table);
    $this->mode = 'view';
    $this->view();
  }
  public function down()
  {
    $this->get();
    priority(1, $this->seq, $this->priority, $this->table);
    $this->mode = 'view';
    $this->view();
  }
  public function bottom()
  {
  }
  public function dup()
  {
    $this->get();
    unset($this->_seq);
    if (is_file($this->image->path()))
      copy($this->image->path(), $this->image->tmp_path());
    $this->image->format(null);
    $this->mode = 'form';
  }*/
}
$_auth      = new Authentication();
$_newsClass = new newsClass();
$_newsClass->main('UTF-8');