<?php
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

if (SessionVarification::get_user ()) {
	echo '{';
	
	$mysql = new MysqlTool ();
	$mysql->connect () or die ( '服务器维护中' );
	
	if (isset ( $_POST ['oldpassword'] ) && 
			isset ( $_POST ['password'] ) && 
			isset ( $_POST ['passwordconfirm'] ) && 
			! preg_match ( '/[^0-9a-zA-Z]+/', $_POST ['oldpassword'] ) && 
			! preg_match ( '/[^0-9a-zA-Z]+/', $_POST ['password'] ) && 
			$_POST ['oldpassword'] !== null && 
			$_POST ['password'] !== null && 
			$_POST ['password'] === $_POST ['passwordconfirm']) {
		
		if ($_POST ['oldpassword'] !== $_POST ['password']) {
			if($mysql->read_operate('select password from users where username="'.SessionVarification::get_user().'"', 1)[0][0] == md5($_POST['oldpassword']))
			{
				$result = $mysql->cdu_operate('update users set password="'.md5($_POST['password']).'"'.' where username="'.SessionVarification::get_user().'"');
				if($result==1 && $mysql->commit()){
					echo "'result':'success'";
					SessionVarification::destroy();//修改成功销毁session
				}
			}
			
		} else {
			if($mysql->read_operate('select password from users where username="'.SessionVarification::get_user().'"', 1)[0][0] == md5($_POST['oldpassword']))
			{
				echo "'result':'success'"; //前后两次密码相同不用修改
				SessionVarification::destroy();//修改成功销毁session
			}
		}
	}
	
	echo '}';
	
	$mysql->close ();
} else {
	header ( "Location:./index.php?" . SID );
}