<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function MODIFY($conn, $openId){
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  $title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
  $content = !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
  $createDate = !empty($_REQUEST['createDate']) ? $_REQUEST['createDate'] : '';
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'Request Error'));
    exit;
  }

  // 更新数据
  $update = $conn->query("UPDATE WX_USER_DIARY_LIST SET 
    title = '$title',
    content = '$content',
    createDate = '$createDate'
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
MODIFY($conn, $openId);

?>