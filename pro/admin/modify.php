<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_MODIFY($conn) {
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
  $role = !empty($_REQUEST['role']) ? $_REQUEST['role'] : '';
  $active = !empty($_REQUEST['active']) ? (int)$_REQUEST['active'] : 1;
  $password_md5 = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';

  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'no id request'));
    exit;
  }

  if (!$role && !$active) {
    RETURNDATA(array('success' => false, 'msg' => 'nothing to update'));
    exit;
  }

  // 更新数据
  $update = $conn->query("UPDATE ADMIN_LIST SET 
    role = '$role',
    active = '$active',
    password_md5 = '$password_md5'
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
ADMIN_MODIFY($conn);

?>