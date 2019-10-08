<?php
// 判断是否登录
function ISLOGIN() {
  // 跨域的域名
  $domain = $_SERVER['HTTP_ORIGIN'];
  // Header中的token
  $UserToken = $_SERVER['HTTP_USERTOKEN'];
  // Cookie中的token
  $CookieToken = $_COOKIE['token'];
  // post请求中的token
  $RequestToken = $_REQUEST['token'];

  $token = $CookieToken;

  // 如果cookie中有token
  if ($token) {
    // 设置过期时间
    $expires = 1800;
    // 开启缓存
    session_start();
    // 如果有该缓存
    if (isset($_SESSION[$token]) && !empty($_SESSION[$token])) {
      // 如果半小时内有操作
      if ($_SESSION[$token]['login_time'] + $expires > time()) {
        // 更新最后登录时间
        $_SESSION[$token]['login_time'] = time();
        return $_SESSION[$token];
      }
      // 否则 销毁该SESSION
      else {
        unset($_SESSION[$token]);
        return 'exit';
      }
    }
  } else {
    // return 'notLogin';
    return false;
  }
}

if (ISLOGIN() === 'exit') {
  echo 'Exited';
  exit;
}

?>
