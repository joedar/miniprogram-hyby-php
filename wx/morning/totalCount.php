<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;
// echo $openId;

function getResult($conn, $openId) {
  // 查询结果
  $result = $conn->query("SELECT * FROM WX_USER_MORNING WHERE openid = '$openId' ORDER BY id DESC");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  RETURNDATA(array('success' => true, 'data' => array('totalCount' => $totalCount)));
}
getResult($conn, $openId);


?>
