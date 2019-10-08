<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.date.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';

// 首先判断是否有登录
if (ISLOGIN() === 'notLogin') { RETURNDATA(array('success' => false, 'msg' => 'please Login')); exit; }

function ADD($conn) {
  // ID
  $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
  if (!$id) { RETURNDATA(array('success' => false, 'msg' => 'no id request')); exit; }

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

  // 更新数据
  $update = $conn->query("UPDATE NEWS_LIST SET 
    title = '$title',
    image = '$image',
    imageType = '$imageType',
    createDate = '$createDate',
    content = '$content',
    contentTxt = '$contentTxt'
    WHERE id = $id
  ");
  if ($update) {
    RETURNDATA(array('success' => true));
  } else {
    RETURNDATA(array('success' => false, 'msg' => 'update fail'));
  }

  // 关闭数据库连接
  $conn->close();
  
}
ADD($conn);

?>