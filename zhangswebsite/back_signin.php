<?php
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

if (SessionVarification::get_user ()) {
	header ( "Location:./index.php?".SID ); // 已登录用户无法访问，跳转至主页
} else {
	
	echo "{";//json大括号
	
	$username = ''; //用户名
	$password = '';//密码
	$sex = '2'; //性别
	$vcode = false;
	
	$mysql = new MysqlTool ();
	$mysql->connect () or die ( '服务器维护中' );
	
	if(isset($_POST['username'])){//查找用户名
		if(!preg_match('/[^0-9a-zA-Z]+/', $_POST['username'])){//用户名为字母或数字
			$resluts=$mysql->read_operate("select username from users WHERE username='" .$_POST['username']."'", 1);
			if($resluts==null && strlen($_POST['username']<=20)){//长度不大于20
				echo "'username':'ok'";//说明用户名可以使用
				$username = $_POST['username'];
			}
		}
	}
	
	if (isset($_POST['securitycode'])){
		if ($_POST['securitycode']==$_SESSION['vcode']&&time()-$_SESSION['vcodetime']<=20){//验证码有效期小于等于20秒
			if($username!=null){
				echo ',';
			}
			echo "'vcode':'ok'";
			$vcode = true;
		}
		
	}
	
	echo "}";//json回括号
	
	if(isset($_POST['sex'])){
		if($_POST['sex']=='male'){$sex = 0;}
		elseif (($_POST['sex']=='female')){$sex=1;}
	}
	
	if($username && $vcode==true && isset($_POST['password']) &&$_POST['password']==$_POST['passwordconfirm'] && !preg_match('/[^0-9a-zA-Z]+/', $_POST['password']) && strlen($_POST['password'])<=30 && strlen($_POST['password'])>0){
		$password = md5($_POST['password']);
		$reslut = $mysql->cdu_operate('insert into users (username,sex,registertime,password) values(?,?,?,?)',
				$username,'s',$sex,'s',date('Y-m-d'),'s',$password,'s');
		if ($reslut == 1 && $mysql->commit()){header ( "Location:./success.php" );}
		else {header ( "Location:./failed.php" );}
	}
	
	$mysql->close();
}