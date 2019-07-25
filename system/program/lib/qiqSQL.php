<?php
/*
* qiqSQL.php: rev.13120601
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*
*/
function sqlSelect($arg)
{
  $distinct = '';
  $column = '';
  $from = '';
  $join = '';
  $where = '';
  $group = '';
  $having = '';
  $order = '';
  $limit = '';
  $offset = '';
  $update = '';
  foreach(array(
    'distinct',
    'column',
    'from',
    'join',
    'where',
    'group',
    'having',
    'order',
    'limit',
    'offset',
    'update'
  ) as $name) if (isset($arg[$name])) $$name = $arg[$name];
  $SQL = 'SELECT ';
  if ($distinct) $SQL.= 'DISTINCT ';
  if ($column) $SQL.= ((is_array($column)) ? implode(', ', $column) : $column);
  if ($from) $SQL.= ' FROM ' . ((is_array($from)) ? implode(', ', $from) : $from);
  if ($join)
  {
    if ($join['type'])
    {
      $join_type = $join['type'];
      $join_from = $join['from'];
      $join_on = $join['on'];
      $SQL.= " $join_type JOIN $join_from ON " . ((is_array($join_on)) ? implode(' AND ', $join_on) : $join_on);
    }
    else
    {
      foreach($join AS $j)
      {
        $join_type = $j['type'];
        $join_from = $j['from'];
        $join_on = $j['on'];
        $SQL.= " $join_type JOIN $join_from ON " . ((is_array($join_on)) ? implode(' AND ', $join_on) : $join_on);
      }
    }
  }
  if ($where) $SQL.= ' WHERE ' . ((is_array($where)) ? implode(' AND ', $where) : $where);
  if ($group) $SQL.= ' GROUP BY ' . ((is_array($group)) ? implode(', ', $group) : $group);
  if ($having) $SQL.= ' HAVING ' . ((is_array($having)) ? implode(' AND ', $having) : $having);
  if ($order) $SQL.= ' ORDER BY ' . ((is_array($order)) ? implode(', ', $order) : $order);
  if ($limit)
  {
    $SQL.= " LIMIT $limit ";
    if ($offset) $SQL.= " OFFSET $offset ";
  }
  if ($update) $SQL.= ' FOR UPDATE ';
  return $SQL;
}
function db_datetime($str, $alias = '')
{
  if (!$alias) $alias = $str;
  switch (DBTYPE)
  {
  case 'pgsql':
    return "EXTRACT('epoch' FROM $str) AS $alias";
  case 'mysql':
    return "UNIX_TIMESTAMP($str) AS $alias";
  case 'mysqli':
    return "UNIX_TIMESTAMP($str) AS $alias";
  default:
    return "$str AS $alias";
  }
}