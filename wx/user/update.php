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
  return $totalCount;
}


function UPDATE($conn, $openId) {
  // 微信用户信息
  $nickName = !empty($_REQUEST['nickName']) ? $_REQUEST['nickName'] : '';
  $gender = !empty($_REQUEST['gender']) ? (int)$_REQUEST['gender'] : 0;
  $language = !empty($_REQUEST['language']) ? $_REQUEST['language'] : '';
  $city = !empty($_REQUEST['city']) ? $_REQUEST['city'] : '';
  $province = !empty($_REQUEST['province']) ? $_REQUEST['province'] : '';
  $country = !empty($_REQUEST['country']) ? $_REQUEST['country'] : '';
  $avatarUrl = !empty($_REQUEST['avatarUrl']) ? $_REQUEST['avatarUrl'] : '';

  // 首先查询是否有该用户
  if (getUser($conn, $openId)) {
    $update = $conn->query("UPDATE WX_USER_LIST SET 
      nickName = '$nickName',
      gender = '$gender',
      language = '$language',
      city = '$city',
      province = '$province',
      country = '$country',
      avatarUrl = '$avatarUrl'
      WHERE openid = '$openId'
    ");
    if ($update) {
      RETURNDATA(array('success' => true));
    } else {
      RETURNDATA(array('success' => false, 'msg' => 'update fail'));
    }
  } else {
    $add = $conn->query("INSERT INTO WX_USER_LIST 
      (id, openid, nickName, gender, language, city, province, country, avatarUrl) 
      VALUES 
      (NULL, '$openId', '$nickName', '$gender', '$language', '$city', '$province', '$country', '$avatarUrl')
    ");
    if ($add) {
      RETURNDATA(array('success' => true));
    } else {
      RETURNDATA(array('success' => false, 'msg' => 'add fail'));
    }
  }
  
  // 关闭数据库连接
  $conn->close();
}
UPDATE($conn, $openId);

?>
