<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include 'login.session.php';

// 首先获取传过来的code
empty($_REQUEST['code']) ? $code = '' : $code = $_REQUEST['code'];

// 如果没有传code，提示错误并退出
if (!$code) {
  RETURNDATA(array('success' => false, 'msg' => 'no code request'));
  exit;
}

// APPID 及 APPSECRET
$appId = '';
$appSecret = '';

// 初始化
$curl = curl_init();
// get请求的链接
$url = 'https://api.weixin.qq.com/sns/jscode2session?' . 'appid='.$appId .'&'. 'secret='.$appSecret .'&'. 'js_code='.$code .'&'. 'grant_type=authorization_code';

function curl_get_https($url){
  // 启动一个CURL会话
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  // 跳过证书检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  // 从证书中检查SSL加密算法是否存在
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
  // 返回api的json对象
  $tmpInfo = curl_exec($curl);
  //关闭URL请求
  curl_close($curl);
  //返回json对象
  return $tmpInfo;
}
$contents = curl_get_https($url);
// 请求接口，并将放回的字符串转换为对象
// $content_obj = json_decode(file_get_contents($url), true);
$content_obj = json_decode($contents, true);

//------------------------------------------------------------------------------------
// {"errcode":40163,"errmsg":"code been used, hints: [ req_id: BJAefz4ce-FEbHLA ]"}
// {"session_key":"t27POz1eHRUb+csk2DgxtQ==","openid":"o9cgg5YJvxMvrCRXFKzCUcd9E6ec"}
//------------------------------------------------------------------------------------
// 如果返回有错误代码，则返回给前端错误码
if (!$content_obj['session_key']) {
  RETURNDATA(array('success' => false, 'msg' => 'errcode is '.$content_obj['errcode']));
  exit;
}

// 得到session_key和openid
$session_key = $content_obj['session_key'];
$openid = $content_obj['openid'];

// 根据session_key和openid 生成token
$hash = md5($session_key . $openid);
$token = str_replace('=', '', base64_encode($hash));


// 设置值为session_key+openid
$value = $session_key . '@' . $openid;
// 设置过期时间为28天
$expire = time() + (3600 * 24 * 28);

// 写入缓存
// $_SESSION[$token] = array('value' => $value, 'expire' => $expire);
Session::set($token, $value, $expire);

// 生成数据返回给前端
$tokenData = array('token' => $token);
RETURNDATA(array('success' => true, 'data' => $tokenData));

?>
