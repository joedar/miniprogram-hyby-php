<?php
// 设置类型及编码
header("Content-type: text/html; charset=utf-8");
// 允许跨域
header("Access-Control-Allow-Origin:*");
// 引入 /config.is.login
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.is.login.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';


if (ISLOGIN() === 'notLogin') {
  RETURNDATA(array('success' => false, 'msg' => 'not login'));
  exit;
}


function getHost() {
  $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
  $host = $_SERVER['HTTP_HOST'];
  return $protocol . $host;
}

function UPLOADIMAGE() {
  //---------------------------------------------------------------
  // 上传图片的文件夹 可根据自己的实际需求更改
  // 上传之后的路径为 https://xxx.com/UPLOAD/images/element/xxx.jpg
  // 但必须以“/”开头
  //---------------------------------------------------------------
  $root_dir = '/UPLOAD/images/element/';

  //---------------------------
  // 限制图片的大小
  // 可以根据自己的实际情况修改
  // 但必须为数值
  //---------------------------
  $max_size = 5;

  ////////////////// 以下都无需修改 //////////////////////

  // 此为前端传来的files[0]对象
  $file = $_FILES['file'] === NULL ? $_FILES['files'] : $_FILES['file'];

  // 校验图片的正则
  $isType = preg_match('/image\/(jpeg|gif|png)/', $file['type']);
  if (!$isType) {
    RETURNDATA(array('success' => false, 'msg' => 'type is wrong . ' .$file['type']));
    exit;
  }

  // 限定图片大小-5M
  $maxSize =  5 * 1024 * 1024;
  if ($_FILES['size'] > $maxSize) {
    RETURNDATA(array('success' => false, 'msg' => '图片不得超过5M'));
    exit;
  }

  // 上传图片路径
  $ROOT = $_SERVER['DOCUMENT_ROOT'] . $root_dir;
  // 创建文件夹
  $dir = iconv('UTF-8', 'GBK', $ROOT);
  if (!file_exists($dir)) mkdir($dir, 0777, true);

  //---------------------
  // 获取文件扩展名
  // 并随机生成文件名
  //---------------------
  $type = '';
  if (preg_match('/image\/jpeg/', $file['type'])) { $type = '.jpg'; }
  elseif (preg_match('/image\/gif/', $file['type'])) { $type = '.gif'; }
  elseif (preg_match('/image\/png/', $file['type'])) { $type = '.png'; }
  // 随机数
  $randomStr = substr(md5(time()), 0, 8) . '!!' . time();
  $filename = $randomStr . $type;
  // 转码 把utf-8转成gb2312,返回转换后的字符串，或者在失败时返回FALSE
  $gb_filename = iconv('utf-8', 'gb2312', $filename);
  // 图片的路径
  $src = '/UPLOAD/images/element/' . $gb_filename;
  // 完整的路径
  $completeUrl = getHost() . $src;

  if (file_exists($filename)) {
    RETURNDATA(array('success' => false, 'msg' => '该文件已存在'));
  } else {
    if (@move_uploaded_file($file['tmp_name'], $ROOT.$gb_filename)) {
      RETURNDATA(array('success' => true, 'data' => array('url' => $completeUrl)));
    } else {
      RETURNDATA(array('success' => false, 'msg' => 'fail'));
    }
  }
}
UPLOADIMAGE();

?>
