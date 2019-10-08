<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_DEL($conn) {
  $openid = !empty($_REQUEST['openid']) ? $_REQUEST['openid'] : '';
  if (!$openid) {
    RETURNDATA(array('success' => false, 'msg' => 'no openid here'));
    exit;
  }

  // 删除
  $delete = $conn->query("DELETE FROM WX_USER_MORNING WHERE openid = '$openid'");
  if ($delete) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'delete fail'));
  }

  // 关闭数据库连接
  $conn->close();
}
ADMIN_DEL($conn);

?>