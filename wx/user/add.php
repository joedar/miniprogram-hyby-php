<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';


// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];
// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function getUser($conn, $openId) {
  // 查询结果
  $result = $conn->query("SELECT * FROM WX_USER_LIST WHERE openid = '$openId'");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  // 结果数组
  $results = $result->fetch_array(MYSQLI_BOTH);
  // 返回
  return (int)$totalCount;
}


function add($conn, $openId) {

  // // 收货人姓名
  // empty($_REQUEST['name']) ? $name = '' : $name = $_REQUEST['name'];
  // // 收货人地址
  // empty($_REQUEST['address']) ? $address = '' : $address = $_REQUEST['address'];
  // // 收货人电话
  // empty($_REQUEST['mobile']) ? $mobile = '' : $mobile = $_REQUEST['mobile'];

  // 是否满足条件写入数据库
  $isInsert = !getUser($conn, $openId);
  // 是否已写入数据库
  $inserted = false;
  // tips
  $errTips = '';

  if ($isInsert) {
    $add = $conn->query("INSERT INTO WX_USER_LIST (id, openid) VALUES (NULL, '$openId')");
    if ($add) {
      $inserted = true;
    } else {
      $inserted = false;
      $errTips = 'insert fail';
    }
  } else {
    $inserted = false;
    $errTips = 'user already exists';
  }

  if ($inserted) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => $errTips));
  }

  // 关闭数据库连接
  $conn->close();
}
add($conn, $openId);

?>
