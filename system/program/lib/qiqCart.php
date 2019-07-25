<?php
/*
* qiqCart.php: rev.13120601
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/
/*
* 使い方：
*
*   $cart = new qiqCart('カート名');
*   $cart->add($seq, $count);    // 追加
*   $cart->remove($seq, $count); // 削除
*   $cart->set($seq, $count);    // 個数指定
*   $cart->delete($seq);   // アイテムごと削除
*   $cart->clear($seq);    // カートの中身クリア
*   $cart->get();      // カートの中身を配列で返す
*/
define('qiqCart_TAG', 'qiqCart');
class qiqCart

{
  // コンストラクタ
  function __construct($name = 'cart')
  {
    $this->name = $name;
    session_start();
  }
  // $seq を $count 個追加
  function add($seq, $count)
  {
    $_SESSION[qiqCart_TAG][$this->name][$seq]+= $count;
  }
  // $seq を $count 個削除
  function remove($seq, $count)
  {
    $_SESSION[qiqCart_TAG][$this->name][$seq]-= $count;
    if ($_SESSION[qiqCart_TAG][$this->name][$seq] <= 0)
    {
      unset($_SESSION[qiqCart_TAG][$this->name][$seq]);
    }
  }
  // $seq を $count 個に設定
  function set($seq, $count)
  {
    $_SESSION[qiqCart_TAG][$this->name][$seq] = $count;
    if ($_SESSION[qiqCart_TAG][$this->name][$seq] <= 0)
    {
      unset($_SESSION[qiqCart_TAG][$this->name][$seq]);
    }
  }
  // $seq を削除
  function delete($seq)
  {
    unset($_SESSION[qiqCart_TAG][$this->name][$seq]);
  }
  // 買い物かごの中身を空にする
  function clear()
  {
    unset($_SESSION[qiqCart_TAG][$this->name]);
  }
  // 買い物かごの中身を配列で返す
  function get()
  {
    return $_SESSION[qiqCart_TAG][$this->name];
  }
}