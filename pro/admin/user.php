<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function USER($conn) {
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'no id request'));
    exit;
  }

  // 查询结果
  $result = $conn->query("SELECT * FROM ADMIN_LIST WHERE id = '$id' limit 1");
  // 查询出来的条数
  $rows = $result->num_rows;
  // 获取该条数据
  $row = $result->fetch_array(MYSQLI_BOTH);

  if ($rows) {
    $detail = array(
      'username' => $row['username'],
      'role' => $row['role'],
      'active' => (int)$row['active']
    );
    RETURNDATA(array('success' => true, 'data' => $detail));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'fail'));
  }
  
  // 关闭数据库连接
  $conn->close();
}
USER($conn);

?>