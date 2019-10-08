<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';
// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];
// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;


function ITEMCOUNT($conn) {
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  $count = !empty($_REQUEST['count']) ? (int)$_REQUEST['count'] : '';
  $update = $conn->query("UPDATE WX_CART_LIST SET 
    count = '$count'
    WHERE id = $id
  ");
  if ($update) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'update fail'));
  }
  // 关闭数据库连接
  $conn->close();
}
ITEMCOUNT($conn);

?>
