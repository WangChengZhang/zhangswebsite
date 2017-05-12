<?php
include_once 'class/class.SessionVarification.php';
include_once 'class/class.MysqlTools.php';

if (SessionVarification::get_user ()) { // 只有登录用户可以访问
	echo '{';
	
	$mysql = new MysqlTool ();
	$mysql->connect () or die ( '服务器维护中' );
	
	if (isset($_POST['articleid']) && 
			isset($_POST['comment']) &&
			strlen($_POST['comment'])<=1000 &&
			strlen($_POST['comment'])>0 &&
			$mysql->read_operate('select articleid from articles where articleid="'.$_POST['articleid'].'"', 1)!=null
			){
		//进行转义，防止混入html标签
		$comment = str_replace(array('<'), '&#60;', $_POST['comment']);
		$comment = str_replace(array('>'), '&#62;', $comment);
		$comment = str_replace(array('&'), '&#38;', $comment);
		$userid = $mysql->read_operate('select userid from users where username="'.SessionVarification::get_user().'"', 1)[0][0];
		$result = $mysql->cdu_operate('insert into comments (articleid,userid,comment) values (?,?,?)',$_POST['articleid'],'i',$userid,'i',$comment,'s');
		if($result==1 && $mysql->commit()){
			echo "'result':'success'";
			$mysql->cdu_operate('update articles set commentnum=commentnum+1 where articleid="'.$_POST['articleid'].'"');
			$mysql->commit();
		}
		
	}
	$mysql->close();
	echo '}';
}
else {
	header ( "Location:./index.php?" . SID );
}