<?php
include_once 'class/class.MySmarty.php';
include_once 'class/class.SessionVarification.php';

if (SessionVarification::get_user ()) {
	header ( "Location:./index.php?".SID ); // 已登录用户无法访问，跳转至主页
} else {
	$mysmarty = new MySmarty ();
	$mysmarty->assign ( 'SID', SID );
	$mysmarty->assign ( 'SESSNAME', session_name() );
	$mysmarty->assign ( 'SESSID', session_id() );
	$mysmarty->assign ( 'csssource', './stylesheets/login.css' );
	$mysmarty->display ( 'topunlogin.tpl' );
	$mysmarty->assign ( 'SID', SID );
	$mysmarty->assign ( 'SESSNAME', session_name() );
	$mysmarty->assign ( 'SESSID', session_id() );
	$mysmarty->display('login.tpl');
	$mysmarty->display ( 'footer.tpl' );
}