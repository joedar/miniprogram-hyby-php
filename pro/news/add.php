<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.date.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

// 首先判断是否有登录
if (ISLOGIN() === 'notLogin') { RETURNDATA(array('success' => false, 'msg' => 'please Login')); exit; }

function ADD($conn) {
  // 新闻标题
  $title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
  if (!$title) { RETURNDATA(array('success' => false, 'msg' => 'no title request')); exit; }

  // 封面图类型
  $imageType = !empty($_REQUEST['imageType']) ? $_REQUEST['imageType'] : '';
  // 封面图
  $image = !empty($_REQUEST['image']) ? $_REQUEST['image'] : '';
  if ($imageType !== 'none' && !$image) {
    if (!$title) { RETURNDATA(array('success' => false, 'msg' => 'no image request')); exit; }
  }

  $createDate = DATES();
  
  // 详情内容
  $content = !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
  if (!$content) { RETURNDATA(array('success' => false, 'msg' => 'no content request')); exit; }
  $content = str_replace('&', '&amp;', $content);
  $content = str_replace('<', '&lt;', $content);
  $content = str_replace('>', '&gt;', $content);

  $contentTxt = !empty($_REQUEST['contentTxt']) ? $_REQUEST['contentTxt'] : '';

  // 增加数据
  $insert = $conn->query("INSERT INTO NEWS_LIST 
    (id, title, image, imageType, createDate, content, contentTxt) 
    VALUES 
    (NULL, '$title', '$image', '$imageType', '$createDate', '$content', '$contentTxt')
  ");
  if ($insert) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'insert fail'));
  }

  // 关闭数据库连接
  $conn->close();
}
ADD($conn);

?>