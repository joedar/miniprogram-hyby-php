<?php
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/config.allow.origin.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/RETURN.DATA.php';
include $_SERVER['DOCUMENT_ROOT'].'/ROOT_HYBYAPI/PG/wx/login.session.php';

// 首先获取传过来的token
empty($_REQUEST['token']) ? $token = '' : $token = $_REQUEST['token'];

// 从session中拿到openID
$openId = Session::get($token) ? Session::get($token) : null;

function getResult($conn, $openId) {
  // 查询结果
  $result = $conn->query("SELECT * FROM WX_USER_MORNING WHERE openid = '$openId' ORDER BY id DESC");
  // 查询出来的总条数
  $totalCount = $result->num_rows;
  // 结果数组
  $results = $result->fetch_array(MYSQLI_NUM);
  // 第一条数据
  $results_first = $results;
  return array(
    'totalCount' => $totalCount,
    'results_first' => $results_first
  );
}

function add($conn, $openId) {
  // 当前的日期
  $recordDate = date('Y/m/d');
  // 服务器当前时间
  $recordTime = time();
  // 当前的几点钟 24小时制
  $hour = (int)date('H');
  // 查询结果
  $results = getResult($conn, $openId);
  // 总条数
  $totalCount = $results['totalCount'];
  // 第一条数据
  $results_first = $results['results_first'];
  // 第一条数据的date
  $results_first_date = $results_first[2] ? $results_first[2] : '';
  // 第一条数据的time
  $results_first_time = $results_first[3] ? (int)$results_first[3] : 0;

  // 时间差 - 当前的time - 数据的time
  $less = $recordTime - $results_first_time;
  // 定义一个布尔值，用以判断是否写进数据库
  $isInsert = false;
  // 提示语
  $tips = '';

  // 如果是在6点前
  if ($hour < 6) { $tips = 'early'; $isInsert = false; }
  // 如果是在6:00~8:59之间
  elseif ($hour >= 6 && $hour < 9) {
    // 如果差值大于21小时，写入数据库
    if ($less > (21 * 3600)) { $tips = 'ok'; $isInsert = true; }
    // 否则 就是已打过卡的
    else { $tips = 'recorded'; $isInsert = false; }
  }
  // 如果是在9:00~23:59之间
  else {
    // 如果差值小21小时，就是已经打过卡的
    if ($less < (21 * 3600)) { $tips = 'recorded'; $isInsert = false; }
    // 否则 就是起的太晚了
    else { $tips = 'late'; $isInsert = false; }
  }

  // 如果满足条件写入数据库
  $inserted = false;
  if ($isInsert) {
    $sql = "INSERT INTO WX_USER_MORNING (id, openid, recordDate, recordTime)
            VALUES (NULL, '$openId', '$recordDate', '$recordTime')";
    if ($conn->query($sql)) {
      $tips = 'ok';
      $inserted = true;
      // 总条数
      $totalCount = getResult($conn, $openId)['totalCount'];
    } else {
      $tips = 'fail';
      $inserted = false;
    }
  }

  // 如果已成功插入数据库
  if ($inserted) {
    RETURNDATA(array('success' => true, 'data' => array('msg' => $tips, 'totalCount' => $totalCount)));
  } else {
    RETURNDATA(array('success' => false, 'msg' => array('msg' => $tips, 'totalCount' => $totalCount)));
  }

  // 关闭数据库连接
  $conn->close();
}
add($conn, $openId);


?>
