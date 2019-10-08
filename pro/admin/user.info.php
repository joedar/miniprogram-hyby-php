<?php
// 设置类型及编码
header("Content-type: text/html; charset=utf-8");
// 允许跨域
header("Access-Control-Allow-Origin:*");
// 引入 /config.is.login
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.is.login.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

// 如果是否登录为 没有登录
if (ISLOGIN() === 'notLogin') {
  RETURNDATA(array('success' => false, 'msg' => 'notLogin'));
  exit;
}

function USERINFO() {
  $token = $_COOKIE['token'];
  if (!$token) {
    RETURNDATA(array('success' => false, 'msg' => 'no token'));
    return;
  }
  // 开启缓存
  session_start();
  // 如果有该缓存
  if (isset($_SESSION[$token]) && !empty($_SESSION[$token])) {
    $user = array(
      'id' => (int)$_SESSION[$token]['id'],
      'username' => $_SESSION[$token]['username'],
      'role' => $_SESSION[$token]['role']
    );
    // 将 token 返回给前端
    RETURNDATA(array('success' => true, 'data' => $user));
  } else {
    RETURNDATA(array('success' => false));
  }
}
USERINFO();

?>