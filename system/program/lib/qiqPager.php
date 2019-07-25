<?php
/*
 * qiqPager.php: rev.14030601
 *
 * Copyright (c) dotAster Inc. <http://www.dotAster.com>
 */
define('qiqPager_DEFAULT_LIMIT', 10);
define('qiqPager_DEFAULT_PAGE_LIMIT', 10);
class qiqPager
{
  public $count = '';
  public $limit = '';
  public $offset = '';
  public $page_limit = '';
  public $query = '';
  public $param = '';
  public $qstr = '';
  public $query_string = '';
  public $pages = array();
  function __construct($param = array())
  {
    $query   = '';
    $default = array(
      'myself' => $this->index_replace(htmlspecialchars($_SERVER['PHP_SELF']), ENT_QUOTES, 'utf-8'),
      'count' => 0,
      'offset' => 0,
      'limit' => qiqPager_DEFAULT_LIMIT,
      'page_limit' => qiqPager_DEFAULT_PAGE_LIMIT,
      'query' => '',
      // 以下、互換用
      'class' => '',
      'current_class' => 'current',
      'no_link_class' => 'noLink',
      'visible_first' => 'off',
      'visible_prev' => 'auto',
      'visible_number' => 'auto',
      'visible_next' => 'auto',
      'visible_last' => 'off',
      'anchor_first' => '&laquo;',
      'anchor_prev' => '&lt;',
      'anchor_next' => '&gt;',
      'anchor_last' => '&raquo;',
      'separator' => ''
    );
    foreach ($default AS $key => $value) {
      $this->$key = (array_key_exists($key, $param)) ? $param[$key] : $value;
    }
    if ($this->param) $this->query = $this->param; // 旧互換
    if ($this->qstr) $this->query = $this->qstr; // 旧互換
    if (is_array($this->query)) {
      foreach ($this->query AS $k => $v) {
        if ($k && $k != 'offset') $query[$k] = $v;  // offset is reserved
      }
      if (is_array($query)) {
        $this->query_string = http_build_query($query);
      }
    }
    if (!empty($param)) $this->calc();
  }
  function calc()
  {
    $pg = array();
    if (!$this->limit)
      $this->limit = qiqPager_DEFAULT_LIMIT;
    if ($this->offset >= $this->count) { // cap
      $this->offset = $this->count - 1;
    }
    $this->offset = floor($this->offset / $this->limit) * $this->limit; // 小数演算は最小限に
    if ($this->offset < 0) {
      $this->offset = 0;
    }
    $this->num_pages  = ceil($this->count / $this->limit);
    $this->cur_page   = $this->offset / $this->limit + 1;
    $this->page_start = floor($this->offset / ($this->limit * $this->page_limit)) * $this->page_limit + 1;
    $this->page_end   = $this->page_start + $this->page_limit - 1;
    if ($this->page_end > $this->num_pages)
      $this->page_end = $this->num_pages;
    for ($i = $this->page_start; $i <= $this->page_end; $i++) {
      $data            = array();
      $data['page']    = $i;
      $data['url']     = sprintf('%s?offset=%d&amp;%s', $this->myself, ($i - 1) * $this->limit, $this->query_string);
      $data['current'] = ($i == $this->cur_page) ? true : false;
      $pg[]            = $data;
    }
    $this->pages = $pg;
    /*
    fblog($this->count, '$this->count');
    fblog($this->limit, '$this->limit');
    fblog($this->offset, '$this->offset');
    fblog($this->num_pages, '$this->num_pages');
    fblog($this->cur_page, '$this->cur_page');
    fblog($this->page_limit, '$this->page_limit');
    fblog($this->page_start, '$this->page_start');
    fblog($this->page_end, '$this->page_end');
    fblog($this->pages, '$this->pages');
    */
  }
  function has_pages()
  {
    return ($this->num_pages > 1) ? true : false;
  }
  function is_first()
  {
    return ($this->cur_page == $this->page_start) ? true : false;
  }
  function has_prev()
  {
    return ($this->offset) ? true : false;
  }
  function has_next()
  {
    return ($this->offset + $this->limit < $this->count) ? true : false;
  }
  function is_last()
  {
    return ($this->cur_page == $this->page_end) ? true : false;
  }
  function first_url()
  {
    return sprintf('%s?%s', $this->myself, $this->query_string);
  }
  function prev_url()
  {
    return sprintf('%s?offset=%d&amp;%s', $this->myself, $this->offset - $this->limit, $this->query_string);
  }
  function next_url()
  {
    return sprintf('%s?offset=%d&amp;%s', $this->myself, $this->offset + $this->limit, $this->query_string);
  }
  function last_url()
  {
    return sprintf('%s?offset=%d&amp;%s', $this->myself, ($this->num_pages - 1) * $this->limit, $this->query_string);
  }
  // 以下、互換ルーチン
  function page_link()
  {
    if ($this->has_prev()) { // 前のページがある
      if ($this->visible_first != 'off') {
        $page_link[] = sprintf('<a href="%s">%s</a>', $this->first_url(), $this->anchor_first);
      }
      if ($this->visible_prev != 'off') {
        $page_link[] = sprintf('<a href="%s">%s</a>', $this->prev_url(), $this->anchor_prev);
      }
    } else {
      if ($this->visible_first == 'on') {
        $page_link[] = sprintf('<span class="%s">%s</span>', $this->no_link_class, $this->anchor_first);
      }
      if ($this->visible_prev == 'on') {
        $page_link[] = sprintf('<span class="%s">%s</span>', $this->no_link_class, $this->anchor_prev);
      }
    }
    if ($this->visible_number == 'on' || ($this->visible_number == 'auto' && $this->num_pages > 1)) {
      foreach ($this->pages AS $data) {
        if ($data['current']) {
          $page_link[] = sprintf('<span class="%s">%d</span>', $this->current_class, $data['page']);
        } else {
          $page_link[] = sprintf('<a href="%s">%d</a>', $data['url'], $data['page']);
        }
      }
    }
    if ($this->has_next()) {
      if ($this->visible_next != 'off') {
        $page_link[] = sprintf('<a href="%s">%s</a>', $this->next_url(), $this->anchor_next);
      }
      if ($this->visible_last != 'off') {
        $page_link[] = sprintf('<a href="%s">%s</a>', $this->last_url(), $this->anchor_last);
      }
    } else {
      if ($this->visible_next == 'on') {
        $page_link[] = sprintf('<span class="%s">%s</span>', $this->no_link_class, $this->anchor_next);
      }
      if ($this->visible_last == 'on') {
        $page_link[] = sprintf('<span class="%s">%s</span>', $this->no_link_class, $this->anchor_last);
      }
    }
    return $page_link;
  }
  function get_links()
  {
    $page_link = $this->page_link();
    return $page_links = ($page_link) ? implode($this->separator, $page_link) : '';
  }
  function get_count_start()
  {
    return max(($this->cur_page - 1) * $this->limit + 1, 0);
  }
  function get_count_end()
  {
    return min($this->count, $this->cur_page * $this->limit);
  }
  function index_replace($url)
  {
    $search  = array(
      'index.php'
    );
    $replace = array(
      ''
    );
    $url     = str_replace($search, $replace, $url);
    return $url;
  }

}