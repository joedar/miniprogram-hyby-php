<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';
// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];
// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;


//-------------------------------------------------------------
// 1、以itemId查询ITEM_LIST得到产品详情
// 2、以(itemId,openId,color)查询WX_CART_LIST得到该条购物车的id
//   如果有该条购物车的id 就只更新该购物车的count
//   如果没有该条购物车id，则新增该条购物车数据
//-------------------------------------------------------------


//----------------------------
// 查询该用户的购物车数据条数
// 将查询结果的totalCount返回
//----------------------------
function getUserCartNum($conn, $openId) {
  $result = $conn->query("SELECT * FROM WX_CART_LIST WHERE openid = '$openId'");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  return (int)$totalCount;
}

//---------------------------
// 查询该条购物车数据是否存在
// 将 id 和 count 返回
//---------------------------
function getCart($OBJ) {
  $conn = $OBJ['conn'];
  $itemId = $OBJ['itemId'];
  $openId = $OBJ['openId'];
  $color = $OBJ['color'];
  // 多条件查询
  $result = $conn->query("SELECT * FROM WX_CART_LIST WHERE itemId = '$itemId' and openid = '$openId' and color = '$color'");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  // 结果数组
  $results = $result->fetch_array(MYSQLI_BOTH);
  return array(
    'id' => $results['id'] ? $results['id'] : null,
    'count' => $results['count'] ? (int)$results['count'] : null
  );
}

//---------------------------
// 查询该商品的详情
// 将结果返回
//---------------------------
function getItemDetail($conn, $itemId) {
  $result = $conn->query("SELECT * FROM ITEM_LIST WHERE id = '$itemId'");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  // 结果数组
  $results = $result->fetch_array(MYSQLI_BOTH);
  // 将结果返回
  return $results;
}


function CARTUPDATE($OBJ, $cart_id, $cart_count) {
  $conn = $OBJ['conn'];
  $cart_count++;
  $update = $conn->query("UPDATE WX_CART_LIST SET 
    count = '$cart_count'
    WHERE id = $cart_id
  ");
  if ($update) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'update fail'));
  }
  // 关闭数据库连接
  $conn->close();
}

function CARTADD($OBJ, $itemDetail) {
  $conn = $OBJ['conn'];
  $itemId = $OBJ['itemId'];
  $openId = $OBJ['openId'];
  $color = $OBJ['color'];
  $title = $itemDetail['title'];
  $price = $itemDetail['price'];
  $zkPrice = $itemDetail['zkPrice'];
  $image = $itemDetail['image'];
  $updateTime = time();

  // 获取该用户的购物车商品数量
  $cartNum = getUserCartNum($conn, $openId);
  // 定义变量已判断是否新增
  $canInsert = $cartNum < 200;

  if ($canInsert) {
    $sql = "INSERT INTO WX_CART_LIST (id, openid, itemId, color, title, price, zkPrice, image, count, updateTime)
          VALUES (NULL, '$openId', '$itemId', '$color', '$title', '$price', '$zkPrice', '$image', 1, '$updateTime')";
    // 添加成功
    if ($conn->query($sql)) {
      RETURNDATA(array('success' => true));
    // 添加失败
    } else {
      RETURNDATA(array('success' => false, 'msg' => 'insert fail'));
    }
  // 不可添加
  } else {
    RETURNDATA(array('success' => false, 'msg' => '购物车已满'));
  }

  // 关闭数据库连接
  $conn->close();
}

function ADD($conn, $openId) {
  // 产品ID
  $itemId = !empty($_REQUEST['itemId']) ? (int)$_REQUEST['itemId'] : '';
  // 所选中的颜色
  $color = !empty($_REQUEST['color']) ? $_REQUEST['color'] : '';
  // 构造OBJ
  $OBJ = array('conn' => $conn, 'openId' => $openId, 'itemId' => $itemId, 'color' => $color);

  // 以itemId获取该商品的详情
  $itemDetail = getItemDetail($conn, $itemId);

  //------------------------------------------
  // 查询是否有相同的数据
  // 如果有相同的数据，就只是更新该条数据的count
  // 如果没有相同数据，就是新增数据
  //------------------------------------------
  $cart = getCart($OBJ);
  $cart_id = $cart['id'];
  $cart_count = $cart['count'];
  if ($cart_id === null) {
    CARTADD($OBJ, $itemDetail);
  } else {
    CARTUPDATE($OBJ, $cart_id, $cart_count);
  }
}
ADD($conn, $openId);


?>
