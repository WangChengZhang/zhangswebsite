<?php
include_once 'class/class.SessionVarification.php';

if (SessionVarification::get_user ()) {
	SessionVarification::destroy();
	header ( "Location:./index.php" ); //跳转至主页
}else{
	header ( "Location:./index.php" ); //跳转至主页
}