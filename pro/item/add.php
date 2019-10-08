<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

// 首先判断是否有登录
if (ISLOGIN() === 'notLogin') { RETURNDATA(array('success' => false, 'msg' => 'please Login')); exit; }

function ADD($conn) {
  // 产品标题
  $title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
  if (!$title) { RETURNDATA(array('success' => false, 'msg' => 'no title request')); exit; }

  // 主图
  $image = !empty($_REQUEST['image']) ? $_REQUEST['image'] : '';
  if (!$image) { RETURNDATA(array('success' => false, 'msg' => 'no image request')); exit; }

  // 原价
  $price = !empty($_REQUEST['price']) ? (int)$_REQUEST['price'] : '';
  if (!$price) { RETURNDATA(array('success' => false, 'msg' => 'no price request')); exit; }
  // 折扣价
  $zkPrice = !empty($_REQUEST['zkPrice']) ? (int)$_REQUEST['zkPrice'] : '';
  if (!$zkPrice) { RETURNDATA(array('success' => false, 'msg' => 'no zkPrice request')); exit; }
  //-----------------
  // 对比价格
  // 折扣价须低于原价
  //-----------------
  if ($price < $zkPrice) { RETURNDATA(array('success' => false, 'msg' => 'incorrect prices')); exit; }
  
  // 折扣类型
  $discountType = !empty($_REQUEST['discountType']) ? $_REQUEST['discountType'] : '';
  
  // 颜色分类
  $colors = !empty($_REQUEST['colors']) ? $_REQUEST['colors'] : '';
  if (!$colors) { RETURNDATA(array('success' => false, 'msg' => 'no colors request')); exit; }
  
  // 是否上架
  $onSale = !empty($_REQUEST['onSale']) ? (int)$_REQUEST['onSale'] : '';
  if (!$onSale) { RETURNDATA(array('success' => false, 'msg' => 'no onSale request')); exit; }
  
  // 产品库存
  $inventory = !empty($_REQUEST['inventory']) ? (int)$_REQUEST['inventory'] : '';
  if (!$inventory) { RETURNDATA(array('success' => false, 'msg' => 'no title request')); exit; }
  
  // 详情内容
  $content = !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
  // if (!$content) { RETURNDATA(array('success' => false, 'msg' => 'no content request')); exit; }
  $content = str_replace('&', '&amp;', $content);
  $content = str_replace('<', '&lt;', $content);
  $content = str_replace('>', '&gt;', $content);

  // 增加数据
  $insert = $conn->query("INSERT INTO ITEM_LIST 
    (id, title, image, price, zkPrice, discountType, colors, onSale, inventory, content) 
    VALUES 
    (NULL, '$title', '$image', '$price', '$zkPrice', '$discountType', '$colors', '$onSale', '$inventory', '$content')
  ");
  if ($insert) {
    RETURNDATA(array('success' => true));
  } else {
    $err = mysql_error() ? mysql_error() : 'insert fail';
    RETURNDATA(array('success' => false, 'msg' => $err));
  }

  // 关闭数据库连接
  $conn->close();
  
}
ADD($conn);

?>