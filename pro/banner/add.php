<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

if (ISLOGIN() === 'notLogin') {
  RETURNDATA(array('success' => false, 'msg' => 'not login'));
  exit;
}

function ADD($conn) {
  $image = !empty($_REQUEST['image']) ? $_REQUEST['image'] : '';

  if (!$image) {
    RETURNDATA(array('success' => false, 'msg' => 'no image'));
    exit;
  }

  // 增加数据
  $insert = $conn->query("INSERT INTO BANNER_LIST (id, image) VALUES (NULL, '$image')");
  if ($insert) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'insert fail'));
  }
  // 关闭数据库连接
  $conn->close();
}
ADD($conn);

?>