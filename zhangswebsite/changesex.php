<?php
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

if(isset($_POST['sex']) && SessionVarification::get_user()){
	$mysql = new MysqlTool();
	$mysql->connect () or die ( '服务器维护中' );
	$username = SessionVarification::get_user();
	
	echo '{';
	
	if($_POST['sex']=='male'){
		if($mysql->read_operate('select sex from users where username="'.$username.'"', 1)[0][0]==='0')
		{echo "'result':'success'";}//和现有性别相同不必修改
		$result = $mysql->cdu_operate('update users set sex="0" where username="'.$username.'"');
	}
	elseif ($_POST['sex']=='female'){
		if($mysql->read_operate('select sex from users where username="'.$username.'"', 1)[0][0]==='1')
		{echo "'result':'success'";}//和现有性别相同不必修改
		$result = $mysql->cdu_operate('update users set sex="1" where username="'.$username.'"');
	}

	
	if ($result==1 && $mysql->commit()){
		echo "'result':'success'";
	}
	echo '}';
	$mysql->close();
}
else {
	header ( "Location:./index.php?".SID );
}
