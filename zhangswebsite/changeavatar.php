<?php
include_once 'class/class.FileUpload.php';
include_once 'class/class.SessionVarification.php';
include_once 'class/class.MysqlTools.php';

if (SessionVarification::get_user ()) { // 只有登录用户可以访问
	FileUpload::$filename = 'avatar';
	
	if (preg_match ( '/.+\.png/i', FileUpload::get_filename () ) && FileUpload::get_filesize () <= 51200) {
		$mysql = new MysqlTool ();
		$mysql->connect () or die ( '服务器维护中' );
		if ($mysql->read_operate ( 'select avatar from users where username="' . SessionVarification::get_user () . '"', 1 ) [0] [0] == null) {
			$result = $mysql->cdu_operate ( 'update users set avatar="./uploadfile/avatars/' . SessionVarification::get_user () . '.png" where username="' . SessionVarification::get_user () . '"' );
			if ($result == 1 && FileUpload::uploads_file ( $root_path . '/uploadfile/avatars/', SessionVarification::get_user () . '.png' ) && $mysql->commit ()) {
				
				header ( "Location:./success.php?" . SID );
			} else {
				
				header ( "Location:./failed.php?" . SID );
			}
		} else {
			if (FileUpload::uploads_file ( $root_path . '/uploadfile/avatars/', SessionVarification::get_user () . '.png' )) {
				header ( "Location:./success.php?" . SID );
			} else {
				
				header ( "Location:./failed.php?" . SID );
			}
		}
	} else {
		header ( "Location:./failed.php?" . SID );
	}
} else {
	header ( "Location:./index.php?" . SID );
}