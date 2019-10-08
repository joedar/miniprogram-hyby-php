<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function add($conn, $openId) {
  $title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
  $content = !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
  $createDate = !empty($_REQUEST['createDate']) ? $_REQUEST['createDate'] : '';
  echo $title;
  echo $content;
  echo $createDate;

  $sql = "INSERT INTO WX_USER_DIARY_LIST (id, openId, title, content, createDate)
          VALUES (NULL, '$openId', '$title', '$content', '$createDate')";
  if ($conn->query($sql)) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'insert fail'));
  }

  // 关闭数据库连接
  $conn->close();
}
add($conn, $openId);


?>
