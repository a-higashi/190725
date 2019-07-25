<?php
require_once 'program/config.php';
require_once 'master.php';
// require_once 'qiqImage.php';
require_once 'ck.php';
// require_once 'category.php';

class newsClass extends qiqFramework
{
  /* DBのテーブル情報 */
  private $table       = 'news';
  /* view一覧 */
  // public $news      = array();
  /* 一覧表示用 */
  public $display      = array();
  /* コンテンツ名 */
  public $item_subject = '新着';
  public $enter_mode   = 0; // 0 だとデフォルトが<p>、1だと<br>
  public $ckListCss    = array();
  public $ckclass      = '';
  public $ckid         = '';

  public function __construct()
  {
    global $labelCategory;
    $this->var        = array(
      'mode',
      'seq',
      'flag',
      'publish',
      'publish_year',
      'publish_month',
      'publish_day',
      'title',
      'category',
      'cat',
      // 'image_tmp',
      // 'image_delete',
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

    $this->import($this->var);
    $this->set_html_dir(HTMLDIR . '/news');

    $this->labelDelete = array(
      1 => '画像を削除'
    );
    $this->labelCategory = array(
      1 => 'お知らせ',
      2 => '薬事関連',
      3 => 'セミナー',
      4 => 'プレスリリース',
      5 => '電子公告',
    );
    /*
    $this->image       = new qiqImage(array(
      'name'    => "image",
      'type'    => 'jpg',
      'tmp_id'  => $this->image_tmp,
      'dir'     => '../upload/news',
      'format'  => 'image%08d.jpg',
      'resize'  => 7,
      'width'   => 960,
      'height'  => 545,
      'tmp_dir' => './tmp'
    ));
    $this->image->format($this->seq);
    $this->thumb       = new qiqImage(array(
      'name'    => "thumb",
      'type'    => 'jpg',
      'tmp_id'  => $this->thumb_tmp,
      'dir'     => '../news/images',
      'format'  => 'thumb%08d.jpg',
      'resize'  => 7,
      'width'   => 265,
      'height'  => 150,
      'tmp_dir' => './tmp'
    ));
    $this->thumb->format($this->seq);
    */
    // ckeditar CSS
    $this->ckListCss   = array(
      // "/css/main.css",
      // "body{max-width: 912px; margin: 10px}",
      // "/system/template/common/css/editor.css"
    );
    $this->labelCheck  = array(
      1 => ''
    );
    $this->labelFlag   = array(
      0 => '非公開',
      1 => '公開'
    );
    if ($this->offset <= 0) $this->offset = 0;
    if ($this->limit <= 0) $this->limit = ADMIN_NEWS_VIEW_LIMIT;
    if (!$this->mode) $this->mode = 'view';
    $this->query        = array('cat' => $this->cat);
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
        // "rec",
        db_datetime('publish'),
        "title",
        "category",
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
    if ($this->_publish) {
      $this->_publish = $this->publish  = date('Y/m/d', $this->_publish);
    }
    // $this->image->format($this->seq);
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
    if ($this->_cat) {
      $values[] = $this->_cat;
      $parameters[] = ':category';
      $types[] = PDO::PARAM_INT;
      $where[] = "category = :category";
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
        // "rec",
        db_datetime('publish'),
        "title",
        "category",
        "body",
        "priority"
      ),
      'from' => $this->table,
      'where' => $where,
      'order' => array(
        "publish DESC",
        "priority DESC"
      ),
      'limit' => ':limit',
      'offset' => ':offset'
    ));
    try {
      $this->stmt = $this->db->prepare($SQL);
      foreach ($values as $key => $value) {
        $this->stmt->bindValue($parameters[$key], $value, $types[$key]);
      }
      $this->stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
      $this->stmt->bindValue(':offset', $this->offset, PDO::PARAM_INT);
      $this->stmt->execute();
      $this->display = array_map('current', $this->stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC));
    }
    catch (PDOException $e) {
      print("LIST PDO::errorInfo(): " . $e->getMessage());
    }
    // ページング
    $this->pager = new qiqPager(array(
      'count' => $this->count,
      'offset' => $this->offset,
      'limit' => $this->limit,
      'query' => $this->query
    ));
    $this->db->close();
  }
  /* フラグ更新 */
  function update()
  {
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用

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
    if (!$this->publish) {
      $this->publish_year  = date('Y');
      $this->publish_month = date('m');
      $this->publish_day   = date('d');
      $this->publish       = sprintf('%04d/%02d/%02d', $this->publish_year, $this->publish_month, $this->publish_day);
    }
  }
  function prepare()
  {
  }
  function check()
  {
    $err = array();
    if (!$this->publish) {
      $err[] = "掲載日を入力してください";
    } elseif (!validate_date($this->publish)) {
      $err[] = "掲載日を正しく入力してください";
    }
    if (!$this->category) {
      $err[] = "カテゴリを選択してください";
    }
    if (!$this->title) {
      $err[] = "タイトルを入力してください";
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
    /*
    $this->image->save_tmp();
    if ($this->cancel) {
      $this->image->purge(true);
      $this->mode = 'view';
      $this->view();
      return;
    }
    */
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
      PDO::PARAM_INT,
      PDO::PARAM_STR
    );
    $columns    = array(
      'flag = :flag',
      // 'rec = :rec',
      'publish = :publish',
      'title = :title',
      'category = :category',
      'body = :body'
    );
    $values     = array(
      $this->flag - 0,
      // $this->rec - 0,
      $this->publish,
      $this->title,
      $this->category,
      $this->body
    );
    $parameters = array(
      ':flag',
      // ':rec',
      ':publish',
      ':title',
      ':category',
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
    /*
    $this->image->format($seq);
    if ($this->image_delete)
      $this->image->delete();
    $this->image->save($seq);
    */
    // thumbnail_image($this->image->path(), $this->thumb->path(), 150, 265);
    // $this->thumb->format($seq);
    // if ($this->thumb_delete)
    //   $this->thumb->delete();
    // $this->thumb->save($seq);
    header("Location: " . MYSELF);
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
    // $this->image->format($this->seq);
    // $this->image->delete();
    header("Location: " . MYSELF);
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