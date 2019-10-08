<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function WX_USER_MORNING($conn) {
  // id
  $openid = !empty($_REQUEST['openid']) ? $_REQUEST['openid'] : '';
  // 页码
  $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
  // 每页多少个
  $pageSize = !empty($_REQUEST['pageSize']) ? (int)$_REQUEST['pageSize'] : 15;

  if (!$openid) {
    RETURNDATA(array('success' => false, 'msg' => 'no openid request'));
    exit;
  }

  $SELECT = 'SELECT * FROM WX_USER_MORNING';
  $ORDER = 'ORDER BY id DESC';
  $LIMIT = '';
  $WHERE = '';

  // 如果有传page / 1(0,15) 2(15,15) 3(30,15)
  if ($page) { $LIMIT = 'limit ' . (($page - 1) * $pageSize) . ',' . $pageSize; }

  // 如果有传openid
  if ($openid) { $WHERE = 'WHERE openid = ' . "'$openid'"; }

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
      'openid' => $row['openid'],
      'recordDate' => $row['recordDate'],
      'recordTime' => $row['recordTime']
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
WX_USER_MORNING($conn);


?>