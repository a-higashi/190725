<?php
// master.php: rev.13160707
function get_label($table, $param = null)
{
  $_label = array();
  $default = array(
    'column'  => 'name',
    'where'   => null,
    'order'   => array(
      'priority'
    ),
    'default' => array(),
    'limit'   => null,
    'offset'  => null
  );
  foreach($default AS $key => $value)
  {
    $$key = ($param && array_key_exists($key, $param)) ? $param[$key] : $value;
  }
  $db = new Database();
  $SQL = sqlSelect(array(
    'column' => array(
      "seq",
      $column
    ) ,
    'from' => $table,
    'where' => $where,
    'order' => $order,
    'limit' => $limit,
    'offset' => $offset
  ));
  try
  {
    $stmt = $db->prepare($SQL);
    $stmt->execute();
    $label = array_map('current', $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC));
  }
  catch(PDOException $e)
  {
    print ("LIST PDO::errorInfo(): " . $e->getMessage());
  }
  foreach($label as $seq => $data)
  {
    $_label[$seq] = $data[$column];
  }
  $db->close();
  return $default + $_label;
}
function get_info($table, $cond)
{
  $db = new Database();
  $where = (is_int($cond)) ? "seq = $cond" : $cond;
  $SQL = sqlSelect(array(
    'column' => '*',
    'from' => $table,
    'where' => $where
  ));
  try
  {
    $stmt = $db->prepare($SQL);
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    print ("GET PDO::errorInfo(): " . $e->getMessage());
  }
  $db->close();
  return $info;
}