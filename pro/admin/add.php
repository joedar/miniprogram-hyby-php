<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_ADD($conn) {
  $username = !empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
  $password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
  $role = !empty($_REQUEST['role']) ? $_REQUEST['role'] : '';

  if (!$username) {
    RETURNDATA(array('success' => false, 'msg' => 'no username request'));
    exit;
  }
  if (!$password) {
    RETURNDATA(array('success' => false, 'msg' => 'no password request'));
    exit;
  }
  if (!$role) {
    RETURNDATA(array('success' => false, 'msg' => 'no role request'));
    exit;
  }

  // 首先查询该管理员是否存在
  $getAdmin = function ($conn, $username) {
    // 查询结果
    $result = $conn->query("SELECT * FROM ADMIN_LIST WHERE username = '$username' ORDER BY id DESC");
    // 查询出来的条数
    $rows = $result->num_rows;
    return $rows && $rows < 2;
  };

  // 用户已存在
  if ($getAdmin($conn, $username)) {
    RETURNDATA(array('success' => false, 'msg' => 'this user already exists'));
    exit;
  }

  // 密码相关
  $password_md5 = '';
  $password_len = strlen($password);
  // 如果密码
  if ($password_len === 32) {
    $password_md5 = $password;
  }
  elseif (($password_len > 16 && $password_len < 32) || $password_len < 6) {
    RETURNDATA(array('success' => false, 'msg' => 'password input is incorrect'));
    exit;
  }
  elseif ($password_len >= 6 && $password_len <= 16) {
    $password_md5 = md5($password);
  }

  // 增加数据
  $insert = $conn->query("INSERT INTO ADMIN_LIST (id, username, password_md5, password, role, active) VALUES (NULL, '$username', '$password_md5', '$password', '$role', 1)");
  if ($insert) {
    RETURNDATA(array('success' => true));
  } else {
    $err = mysql_error() ? mysql_error() : 'insert fail';
    RETURNDATA(array('success' => false, 'msg' => $err));
  }

  // 关闭数据库连接
  $conn->close();
}
ADMIN_ADD($conn);

?>