<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

// 如果是否登录为 没有登录
if (ISLOGIN() === 'notLogin') {
  RETURNDATA(array('success' => false, 'msg' => 'notLogin'));
} else {
  RETURNDATA(array('success' => true));
}

?>
