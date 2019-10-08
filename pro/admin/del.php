<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_DEL($conn) {
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'no id here'));
    exit;
  }

  // 已获得token
  $token = $_COOKIE['token'];
  // 如果cookie中有token
  if ($token) {
    // 开启缓存
    session_start();
    // 如果有该缓存
    if (isset($_SESSION[$token]) && !empty($_SESSION[$token])) {
      // 如果缓存中的id 与 传来的id 相等
      if ((int)$_SESSION[$token]['id'] === (int)$id) {
        // 返回false 并 退出
        RETURNDATA(array('success' => false, 'msg' => 'can not delete self'));
        exit;
      }
    }
  }

  // 删除
  $delete = $conn->query("DELETE FROM ADMIN_LIST WHERE id = $id");
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