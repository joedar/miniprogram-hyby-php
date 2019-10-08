<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function DEL($conn) {
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'no id here'));
    exit;
  }

  // 删除
  $delete = $conn->query("DELETE FROM WX_USER_DIARY_LIST WHERE id = $id");
  if ($delete) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'delete fail'));
  }

  // 关闭数据库连接
  $conn->close();
}
DEL($conn);

?>