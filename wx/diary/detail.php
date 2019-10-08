<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function DETAIL($conn, $openId){
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'Request Error'));
    exit;
  }

  $result = $conn->query("SELECT * FROM WX_USER_DIARY_LIST WHERE id = '$id' and openId = '$openId'");
  // 查询出来的条数
  $rows = $result->num_rows;
  // 获取该条数据
  $row = $result->fetch_array(MYSQLI_BOTH);

  if ($rows) {
    $detail = array(
      'id' => (int)$row['id'],
      'title' => $row['title'],
      'content' => $row['content'],
      'createDate' => $row['createDate']
    );
    RETURNDATA(array('success' => true, 'data' => $detail));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'fail'));
  }
  
  // 关闭数据库连接
  $conn->close();
}
DETAIL($conn, $openId);

?>