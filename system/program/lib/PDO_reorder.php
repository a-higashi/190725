<?php
// reorder.php: rev.13120601
function priority($mode, $seq, $priority, $table, $where = array())
{
  $db = new Database();
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
  $stmt = $db->prepare($SQL);
  $stmt->execute();
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($data)
  {
    try
    {
      $db->beginTransaction();
      $stmt = $db->prepare("UPDATE $table SET priority = $priority WHERE seq = {$data['seq']}");
      $stmt->execute();
      $stmt = $db->prepare("UPDATE $table SET priority = {$data['priority']} WHERE seq = $seq");
      $stmt->execute();
      $db->commit();
    }
    catch(PDOException $e)
    {
      $this->db->rollBack(); // トランザクション用
      print "UPDATE PDO::errorInfo(): " . $e->getMessage();
      die();
    }
  }
  $db->close();
  return true;
}
