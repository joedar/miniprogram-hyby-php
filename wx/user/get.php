<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];
// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;


function get_access_token() {
  // APPID 及 APPSECRET
  $appId = '';
  $appSecret = '';
  $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential'. '&appid='.$appId. '&secret='.$appSecret;
  // 启动一个CURL会话
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  // 跳过证书检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  // 返回api的json对象
  $tmpInfo = curl_exec($curl);
  //关闭URL请求
  curl_close($curl);
  // 将字符串转换为对象
  $obj = json_decode($tmpInfo, true);
  //返回json对象
  return $obj['access_token'];
}


function get_user_info($access_token) {
  $openId = 'o9cgg5YJvxMvrCRXFKzCUcd9E6ec';
  $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openId.'&lang=zh_CN';
  // 启动一个CURL会话
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  // 跳过证书检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  // 返回api的json对象
  $tmpInfo = curl_exec($curl);
  //关闭URL请求
  curl_close($curl);
  return $tmpInfo;
}
$access_token = get_access_token();
$user_info = get_user_info($access_token);
var_dump($user_info);



// function curl_get_https($url){
//   // 启动一个CURL会话
//   $curl = curl_init();
//   curl_setopt($curl, CURLOPT_URL, $url);
//   curl_setopt($curl, CURLOPT_HEADER, 0);
//   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//   // 跳过证书检查
//   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//   // 从证书中检查SSL加密算法是否存在
//   // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
//   // 返回api的json对象
//   $tmpInfo = curl_exec($curl);
//   //关闭URL请求
//   curl_close($curl);
//   //返回json对象
//   return $tmpInfo;
// }


// function add($conn, $openId) {


//   // 关闭数据库连接
//   $conn->close();
// }
// add($conn, $openId);

?>
