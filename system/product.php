<?php
require_once 'program/config.php';
require_once 'master.php';
require_once 'qiqImage.php';
require_once 'qiqUpload.php';
require_once 'ck.php';
require_once 'common.php';

class newsClass extends qiqFramework
{
  /* DBのテーブル情報 */
  private $table       = 'product';
  /* view一覧 */
  // public $news      = array();
  /* 一覧表示用 */
  public $display      = array();
  /* コンテンツ名 */
  public $item_subject = '製品';
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
      'category',
      'cat',
      'title',
      'firm',
      'subtitle',
      'formula',
      'effection',
      'feature',
      'description',
      'image_tmp',
      'image_delete',
      'pdf',
      'pdf_fname',
      'pdf_tmp',
      'pdf_delete',
      'pdf_ext',
      'sds',
      'sds_fname',
      'sds_tmp',
      'sds_delete',
      'sds_ext',
      'main_material',
      'product_group',
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
    $this->set_html_dir(HTMLDIR . '/product');

    $this->labelDelete = array(
      1 => '画像を削除'
    );
    $this->labelCategory = category();
    if(isset($this->cat)) {
      $this->labelMaterial = material($this->cat);
      $this->labelProduct  = product($this->cat);
    } else {
      $this->labelMaterial = material();
      $this->labelProduct  = product();
    }
    $this->image       = new qiqImage(array(
      'name'    => "image",
      'type'    => 'jpg',
      'tmp_id'  => $this->image_tmp,
      'dir'     => '../upload/product',
      'format'  => 'image%08d.jpg',
      'resize'  => 7,
      'width'   => 600,
      'height'  => 400,
      'tmp_dir' => './tmp'
    ));
    $this->image->format($this->seq);
    $this->pdf = new qiqUpload(array(
      'name'    => "pdf",
      'tmp_id'  => $this->pdf_tmp,
      'dir'     => '../upload/product_pdf',
      'format'  => 'pdf%08d%s',
      'ext'     => $this->pdf_ext,
      'tmp_dir' => '../upload/product_pdf'
    ));
    $this->pdf->format($this->seq, $this->pdf->ext);
    $this->sds = new qiqUpload(array(
      'name'    => "sds",
      'tmp_id'  => $this->sds_tmp,
      'dir'     => '../upload/product_sds',
      'format'  => 'sds%08d%s',
      'ext'     => $this->sds_ext,
      'tmp_dir' => '../upload/product_sds'
    ));
    $this->sds->format($this->seq, $this->sds->ext);
    /*
    $this->labelCategory = array(
      1 => '',
    );
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
        db_datetime('publish'),
        "category",
        "title",
        "firm",
        "subtitle",
        "formula",
        "effection",
        "feature",
        "description",
        "pdf_fname",
        "sds_fname",
        "main_material",
        "product_group",
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
    if($this->main_material) {
      $main_material = explode(',', $this->main_material);
      foreach ($main_material as $value) {
        $this->main_material_check[$value] = $value;
      }
    }
    if($this->product_group) {
      $product_group = explode(',', $this->product_group);
      foreach ($product_group as $key => $value) {
        $this->product_group_check[$value] = $value; 
      }
    }
    $this->image->format($this->seq);
    $this->pdf->ext = ext($this->pdf_fname);
    $this->pdf->format($this->seq, $this->pdf->ext);
    $this->sds->ext = ext($this->sds_fname);
    $this->sds->format($this->seq, $this->sds->ext);
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
    if(isset($_SERVER['SCRIPT_FILENAME']) && (strpos($_SERVER['SCRIPT_FILENAME'], 'system') === FALSE)) {
      $where[] = 'flag = 1';
      $where[] = 'publish <= now()';
    }
    if ($this->_cat) {
      $values[] = '%'.$this->labelCategory[$this->_cat].'%';
      $parameters[] = ':category';
      $types[] = PDO::PARAM_STR;
      $where[] = "category LIKE :category";
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
        "category",
        "title",
        "firm",
        "subtitle",
        "formula",
        "effection",
        "feature",
        "description",
        "pdf_fname",
        "sds_fname",
        "main_material",
        "product_group",
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
    if(!$this->category)
      $this->category = $this->_category = $this->labelCategory[$this->cat];
  }
  function prepare()
  {
    $this->pdf_fname = _basename($this->pdf_fname);
    $this->escape(array(
      'pdf_fname'
    ));
    $this->sds_fname = _basename($this->sds_fname);
    $this->escape(array(
      'sds_fname'
    ));
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
    if (!$this->title) {
      $err[] = "製品名を入力してください";
    }
    if (!$this->body) {
      $err[] = "本文を入力してください";
    }
    */
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
    $this->pdf->save_tmp();
    $this->sds->save_tmp();
    if ($this->cancel) {
      $this->image->purge(true);
      $this->pdf->purge(true);
      $this->sds->purge(true);
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
    if(!empty($this->main_material)) {
      $main_material = implode(',', $this->main_material);
    } else {
      $main_material = '';
    }
    if(!empty($this->product_group)) {
      $product_group = implode(',', $this->product_group);
    } else {
      $product_group = '';
    }
    $this->db = new Database();
    $this->db->beginTransaction(); // トランザクション用
    $types      = array(
      PDO::PARAM_INT,
      PDO::PARAM_INT,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR,
      PDO::PARAM_STR
    );
    $columns    = array(
      'flag = :flag',
      'publish = :publish',
      'category = :category',
      'title = :title',
      'firm = :firm',
      'subtitle = :subtitle',
      'formula = :formula',
      'effection = :effection',
      'feature = :feature',
      'description = :description',
      'pdf_fname = :pdf_fname',
      'sds_fname = :sds_fname',
      'main_material = :main_material',
      'product_group = :product_group',
      'body = :body'
    );
    $values     = array(
      $this->flag - 0,
      $this->publish,
      $this->category,
      $this->title,
      $this->firm,
      $this->subtitle,
      $this->formula,
      $this->effection,
      $this->feature,
      $this->description,
      $this->pdf_fname,
      $this->sds_fname,
      $main_material,
      $product_group,
      $this->body
    );
    $parameters = array(
      ':flag',
      ':publish',
      ':category',
      ':title',
      ':firm',
      ':subtitle',
      ':formula',
      ':effection',
      ':feature',
      ':description',
      ':pdf_fname',
      ':sds_fname',
      ':main_material',
      ':product_group',
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
    if ($this->image_delete)
      $this->image->delete();
    $this->image->save($seq);
    // thumbnail_image($this->image->path(), $this->thumb->path(), 150, 265);
    // $this->thumb->format($seq);
    // if ($this->thumb_delete)
    //   $this->thumb->delete();
    // $this->thumb->save($seq);
    $this->pdf->format($seq, ext($this->pdf_fname));
    if ($this->pdf_delete) $this->pdf->delete();
    $this->pdf->save($seq);
    $this->sds->format($seq, ext($this->sds_fname));
    if ($this->sds_delete) $this->sds->delete();
    $this->sds->save($seq);
    if($this->cat) {
      header("Location: " . MYSELF . "?cat=" . $this->cat);
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
    $this->image->format($this->seq);
    $this->image->delete();
    $this->pdf->format($this->seq, ext($this->pdf_fname));
    $this->pdf->delete();
    $this->sds->format($this->seq, ext($this->sds_fname));
    $this->sds->delete();
    if($this->cat) {
      header("Location: " . MYSELF . "?cat=" . $this->cat);
    } else {
      header("Location: " . MYSELF);
    }
    // $this->mode = 'view';
    // $this->view();
  }
/*
  public function top()
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
  }
*/
}
$_auth      = new Authentication();
$_newsClass = new newsClass();
$_newsClass->main('UTF-8');