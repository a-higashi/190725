<?php
require_once 'program/config.php';
require_once 'master.php';
require_once 'qiqImage.php';
require_once 'ck_shunen.php';

class newsClass extends qiqFramework
{
  /* DBのテーブル情報 */
  private $table       = 'news_shunen';
  /* view一覧 */
  // public $news      = array();
  /* 一覧表示用 */
  public $display      = array();
  /* コンテンツ名 */
  public $item_subject = '周年イントラ新着';
  public $enter_mode   = 1; // 0 だとデフォルトが<p>、1だと<br>
  public $ckListCss    = array();
  public $ckclass      = 'contents';
  public $ckid         = '';
  public $width        = 724;

  public function __construct()
  {
    parent::__construct();
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
      'image_tmp',
      'image_delete',
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
    // ckeditar CSS
    $this->ckListCss  = array(
      '/united_one/lib/cmn_css/base.css',
      '/united_one/css/uniq.css',
      '/united_one/lib/cmn_css/base.css',
      '/united_one/lib/cmn_js/qswow/qswow.css',
      '.contents { font-size: 13px; color: #000;}',
      "body{max-width: {$this->width}px; margin: 10px}"
    );

    $this->import($this->var);
    $this->set_html_dir(HTMLDIR . '/news_shunen');

    $this->labelDelete = array(
      1 => '画像を削除'
    );

    $this->labelCategory = array(
      1 => '周年コンセプトワーク',
      2 => 'WEBサイト',
      3 => 'フィラーブック制作',
      4 => '社史・記念誌制作',
      5 => '社内報',
      6 => 'イントラ',
      7 => '動画制作',
      8 => '創立100周年記念式典',
      9 => 'Q&Aコーナー',
    );
    $this->image       = new qiqImage(array(
      'name'    => "image",
      'type'    => 'jpg',
      'tmp_id'  => $this->image_tmp,
      'dir'     => '../united_one/upload/image',
      'format'  => 'image%08d.jpg',
      'resize'  => 5,
      'width'   => 282,
      'height'  => 210,
      'tmp_dir' => './tmp'
    ));
    $this->image->format($this->seq);

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

    $this->image->format($this->seq);

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
    $this->image->save_tmp();

    if ($this->cancel) {
      $this->image->purge(true);
      $this->mode = 'view';
      $this->view();
      return;
    }

    $this->prepare();
    if (!$this->check()) {
      $this->mode = 'form';
      return;
    }
    $this->flag  = $this->_flag = $this->_flag  - 0;
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
      PDO::PARAM_INT,
      PDO::PARAM_STR,
      PDO::PARAM_INT,
      PDO::PARAM_STR
    );
    $columns    = array(
      'flag = :flag',
      'publish = :publish',
      'title = :title',
      'category = :category',
      'body = :body'
    );
    $values     = array(
      $this->flag - 0,
      $this->publish,
      $this->title,
      $this->category,
      $this->body
    );
    $parameters = array(
      ':flag',
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
    $this->image->format($seq);
    if ($this->image_delete) $this->image->delete();
    $this->image->save($seq);

    header('Location: ' . MYSELF . '?' . $this->query_string);
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

    $this->image->format($this->seq);
    $this->image->delete();

    header('Location: ' . MYSELF . '?' . $this->query_string);
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