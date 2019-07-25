<?php
// reorder.php: rev.13120601
function priority($mode, $seq, $priority, $table, $where = array())
{
  $db = qiqDB::connect(DSN);
  if (qiqDB::isError($db))
  {
    return false;
  }
  if ($mode < 0)
  {
    $where[] = "priority > $priority";
    $order = "priority";
  }
  else
  {
    $where[] = "priority < $priority";
    $order = "priority DESC";
  }
  $SQL = sqlSelect(array(
    'column' => array(
      "seq",
      "priority"
    ) ,
    'from' => "$table",
    'where' => $where,
    'order' => $order,
    'limit' => 1
  ));
  $data = $db->extended->getRow($SQL, null, null, null, MDB2_FETCHMODE_ASSOC);
  if (qiqDB::isError($data))
  {
    return false;
  }
  if ($data)
  {
    $db->query("BEGIN");
    if (qiqDB::isError($res))
    {
      return false;
    }
    $db->query("UPDATE $table SET priority = $priority WHERE seq = {$data['seq']}");
    if (qiqDB::isError($res))
    {
      $db->query("ABORT");
      return false;
    }
    $db->query("UPDATE $table SET priority = {$data['priority']} WHERE seq = $seq");
    if (qiqDB::isError($res))
    {
      $db->query("ABORT");
      return false;
    }
    $db->query("COMMIT");
    $res = $db->query($SQL);
    if (qiqDB::isError($res))
    {
      $db->query("ABORT");
      return false;
    }
  }
  return true;
}