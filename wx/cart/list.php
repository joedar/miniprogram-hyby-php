<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function GETLIST($conn, $openId) {

  $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
  $pageSize = !empty($_REQUEST['pageSize']) ? (int)$_REQUEST['pageSize'] : 15;
  // 如果有传page和pageSize
  $limit = '';
  if($page && $pageSize){$limit = 'limit '.(($page - 1) * $pageSize).','.$pageSize;}

  // 查询结果
  $result = $conn->query("SELECT * FROM WX_CART_LIST WHERE openid = '$openId' ORDER BY id DESC $limit");
  // 查询出来的总条数
  $totalCount = $result->num_rows;

  $rows = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $obj = array(
      'id' => (int)$row['id'],
      'itemId' => (int)$row['itemId'],
      'title' => $row['title'],
      'image' => $row['image'],
      'color' => $row['color'],
      'price' => (int)$row['price'],
      'zkPrice' => (int)$row['zkPrice'],
      'count' => (int)$row['count']
    );
    $rows[] = $obj;
  }

  if ($rows) {
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