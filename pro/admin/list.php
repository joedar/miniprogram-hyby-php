<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_LIST($conn) {
  // id
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  // 角色
  $role = !empty($_REQUEST['role']) ? $_REQUEST['role'] : '';
  // 页码
  $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
  // 每页多少个
  $pageSize = !empty($_REQUEST['pageSize']) ? (int)$_REQUEST['pageSize'] : 15;

  $SELECT = 'SELECT * FROM ADMIN_LIST';
  $ORDER = 'ORDER BY id DESC';
  $LIMIT = '';
  $WHERE = '';

  // 如果有传page / 1(0,15) 2(15,15) 3(30,15)
  if ($page) { $LIMIT = 'limit ' . (($page - 1) * $pageSize) . ',' . $pageSize; }

  // 如果有传id
  if ($id) { $WHERE = 'WHERE id = ' . $id; $ORDER = ''; }

  // 如果有传角色
  if ($role) { $WHERE = 'WHERE role = ' . "'$role'"; }

  // 查询总的结果
  $total = $conn->query("$SELECT $WHERE $ORDER");
  // 查询出来总的条数
  $totalCount = $total->num_rows;

  // 按翻页查询的结果
  $list = array();
  $result = $conn->query("$SELECT $WHERE $ORDER $LIMIT");
  while ($row = mysqli_fetch_assoc($result)) {
    $obj = array(
      'id' => $row['id'],
      'username' => $row['username'],
      'role' => $row['role'],
      'active' => $row['active']
    );
    $list[] = $obj;
  }

  $data = array(
    'list' => $list,
    'thisPage' => $page,
    'totalCount' => $totalCount
  );
  RETURNDATA(array('success' => true, 'data' => $data));

  // 关闭数据库连接
  $conn->close();
}
ADMIN_LIST($conn);


?>