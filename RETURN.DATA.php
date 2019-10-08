<?php
function RETURNDATA($OBJ){
	if($OBJ['success']){
		// 如果有返回数据
		if(!empty($OBJ['data'])){
			$ECHO = array('success' => 'true', 'data' => $OBJ['data']);
		}
		else{
			$ECHO = array('success' => 'true');
		}
	}
	else{
		$ECHO = array('success' => 'false', 'msg' => $OBJ['msg']);
	}
	$ECHO = json_encode($ECHO);
	echo $ECHO;
}
?>