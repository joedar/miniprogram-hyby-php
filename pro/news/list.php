<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function GETLIST($conn) {
  // 页码
  $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
  // 每页多少个
  $pageSize = !empty($_REQUEST['pageSize']) ? (int)$_REQUEST['pageSize'] : 15;

  $SELECT = 'SELECT * FROM NEWS_LIST';
  $ORDER = 'ORDER BY id DESC';
  $LIMIT = '';

  // 如果有传page / 1(0,15) 2(15,15) 3(30,15)
  if ($page) { $LIMIT = 'limit ' . (($page - 1) * $pageSize) . ',' . $pageSize; }

  // 查询总的结果
  $total = $conn->query("$SELECT $ORDER");
  // 查询出来总的条数
  $totalCount = $total->num_rows;

  // 按翻页查询的结果
  $list = array();
  $result = $conn->query("$SELECT $ORDER $LIMIT");
  while ($row = mysqli_fetch_assoc($result)) {
    $obj = array(
      'id' => $row['id'],
      'title' => $row['title'],
      'imageType' => $row['imageType'],
      'image' => $row['image'],
      'createDate' => $row['createDate'],
      'content' => $row['content'],
      'contentTxt' => $row['contentTxt']
    );
    $list[] = $obj;
  }

  $data = array(
    'list' => $list,
    'thisPage' => $page,
    'totalCount' => $totalCount
  );
  RETURNDATA(array('success' => true, 'data' => $data));

  // 关闭数据库连接
  $conn->close();
}
GETLIST($conn);


?>