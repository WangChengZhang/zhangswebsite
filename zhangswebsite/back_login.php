<?php
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

if (SessionVarification::get_user ()) {
	header ( "Location:./index.php?".SID ); // 已登录用户无法访问，跳转至主页
} else {
	
	echo '{';
	$username = ''; // 用户名
	$password = ''; // 密码
	$vcode = false;
	$result = array ();
	
	$mysql = new MysqlTool ();
	$mysql->connect () or die ( '服务器维护中' );
	
	if (isset ( $_POST ['securitycode'] )) {
		if ($_POST ['securitycode'] == $_SESSION ['vcode'] && time () - $_SESSION ['vcodetime'] <= 20) { // 验证码有效期小于等于20秒
			echo "'vcode':'ok'";
			$vcode = true;
		}
	}
	
	if (isset ( $_POST ['username'] ) && isset ( $_POST ['password'] ) && $vcode == true && ! preg_match ( '/[^0-9a-zA-Z]+/', $_POST ['username'] ) && ! preg_match ( '/[^0-9a-zA-Z]+/', $_POST ['password'] ) && strlen ( $_POST ['username'] ) <= 20 && strlen ( $_POST ['password'] ) <= 30) {
		$username = $_POST ['username'];
		$password = md5 ( $_POST ['password'] );
		if (($result = $mysql->read_operate ( 'select password from users where username="' . $username . '"', 1 ))!= null && $password == $result [0] [0]) {
			SessionVarification::set_user ( $username );
			echo ",'result':'success'";
		} else {
			if ($vcode == true) {
				echo ',';
			}
			echo "'result':'fail'";
		}
	}
	$mysql->close();
	echo '}';
}