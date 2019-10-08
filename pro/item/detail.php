<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

function DETAIL($conn){
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  if (!$id) {
    RETURNDATA(array('success' => false, 'msg' => 'Request Error'));
    exit;
  }

  $result = $conn->query("SELECT * FROM ITEM_LIST WHERE id = '$id' limit 1");
  // 查询出来的条数
  $rows = $result->num_rows;
  // 获取该条数据
  $row = $result->fetch_array(MYSQLI_BOTH);

  if ($rows) {
    $colorsArr = explode(',', $row['colors']);
    $discountTypeArr = !empty($row['discountType']) ? explode(',', $row['discountType']): array();
    $detail = array(
      'id' => (int)$row['id'],
      'title' => $row['title'],
      'image' => $row['image'],
      'price' => (int)$row['price'],
      'zkPrice' => (int)$row['zkPrice'],
      'discountType' => $discountTypeArr,
      'colors' => $colorsArr,
      'onSale' => (int)$row['onSale'],
      'inventory' => (int)$row['inventory'],
      'content' => $row['content']
    );
    RETURNDATA(array('success' => true, 'data' => $detail));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'fail'));
  }
  
  // 关闭数据库连接
  $conn->close();
}
DETAIL($conn);

?>