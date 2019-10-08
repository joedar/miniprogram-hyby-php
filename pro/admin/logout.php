<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
// 用来判断是否登录
session_start();
// 判断是否已登录
$isLogin = (isset($_SESSION['adminLogin']) && !empty($_SESSION['adminLogin'])) ? true : false;
// 如果已登录
if ($isLogin) {
  // 销毁登录
  unset($_SESSION['adminLogin']);
  exit;
}
?>