<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function ADMIN_LOGIN($conn) {
  // 获取传来的username和password
  $username = !empty($_REQUEST['username']) ? htmlspecialchars($_REQUEST['username']) : '';
  $password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';

  // 校验是否有传username
  if (!$username) {
    RETURNDATA(array('success' => false, 'msg' => 'no username request'));
    exit;
  }
  // 校验是否有传password
  if (!$password) {
    RETURNDATA(array('success' => false, 'msg' => 'no password request'));
    exit;
  }

  // 首先查询该管理员是否存在
  $getAdmin = function($conn, $username) {
    // 查询结果
    $result = $conn->query("SELECT * FROM ADMIN_LIST WHERE username = '$username' limit 1");
    // 查询出来的条数
    $rows = $result->num_rows;
    return $rows;
  };

  // 用户不存在
  if (!$getAdmin($conn, $username)) {
    RETURNDATA(array('success' => false, 'msg' => 'This user does not exist'));
    exit;
  }

  // 查询结果
  $login = $conn->query("SELECT * FROM ADMIN_LIST WHERE username = '$username' and password_md5 = '$password' limit 1");
  // 查询出来的条数
  $login_rows = $login->num_rows;
  
  
  // 如果存在，就表示用户名密码完全正确
  if ($login_rows) {
    // 获取该条数据
    $logined = $login->fetch_array(MYSQLI_BOTH);

    // 是否激活
    $active = $logined['active'] ? (int)$logined['active'] : 0;
    // 如果是激活状态 可以登录
    if ($active) {
      // 当前的时间
      $time = time();
      // 根据 $username 和 $time 生成 token
      $hash = md5($username . $time);
      $token = str_replace('=', '', base64_encode($hash));
      // 开启session
      session_start();
      // 以 token 作为键 写入缓存
      $_SESSION[$token] = array(
        'id' => $logined['id'],
        'username' => $username,
        'role' => $logined['role'],
        'active' => $logined['active'],
        'login_time' => $time
      );
      // 将 token 返回给前端
      RETURNDATA(array('success' => true, 'data' => array('token' => $token)));
    }
    // 否则就是非激活状态 不可登录
    else {
      RETURNDATA(array('success' => false, 'msg' => 'No permission to log in'));
    }
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'Incorrect username and password'));
  }

  // 关闭数据库连接
  $conn->close();
}
ADMIN_LOGIN($conn);


?>