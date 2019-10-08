<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;
// echo 'openid = '.$openId;

function GETLIST($conn, $openId) {

  $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
  $pageSize = !empty($_REQUEST['pageSize']) ? (int)$_REQUEST['pageSize'] : 15;
  // 如果有传page和pageSize
  $limit = '';
  if($page && $pageSize){$limit = 'limit '.(($page - 1) * $pageSize).','.$pageSize;}

  // 查询结果
  $result = $conn->query("SELECT * FROM WX_USER_DIARY_LIST WHERE openid = '$openId' ORDER BY id DESC $limit");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  // 读取列表 [MYSQLI_NUM | MYSQLI_ASSOC | MYSQLI_BOTH]
  // $row = $result->fetch_array(MYSQLI_ASSOC);
  // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  // $row = mysqli_fetch_assoc($result);

  $rows = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $obj = array(
      'id' => (int)$row['id'],
      'title' => $row['title'],
      'content' => $row['content'],
      'createDate' => $row['createDate']
    );
    $rows[] = $obj;
  }
  // echo $totalCount;

  if ($result) {
    $data = array(
      'list' => $rows
      ,'thisPage' => $page
      ,'totalCount' => $totalCount
    );
    RETURNDATA(array('success' => true, 'data' => $data));
  }else{
    $err = mysql_error() ? mysql_error() : 'no data';
    RETURNDATA(array('success' => false, 'msg' => $err));
  }

  // 关闭数据库连接
  $conn->close();
}
GETLIST($conn, $openId);




?>