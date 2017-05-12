<?php 
include_once 'class/class.MySmarty.php';
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

if(!isset($_GET['username'])){
	header ( "Location:./index.php?".SID );//必须传递用户名才可访问
}
else{
	$username = '';
	$userid = '';
	
	$mysql = new MysqlTool();
	$mysql->connect () or die ( '服务器维护中' );
	$result=$mysql->read_operate('select userid from users where username="'.$_GET['username'].'"', 1);
	if($result[0][0]===null){
		header ( "Location:./index.php?".SID );//用户不存在也不可以访问
	}
	else {
		$username = $_GET['username'];
		$totalcomments;
		$userid = $result[0][0];
		$result=$mysql->read_operate('select COUNT(*) from comments where userid="'.$result[0][0].'"', 1);
		$totalcomments = $result[0][0];
		
		// 显示头部
		$mysmarty = new MySmarty ();
		$mysmarty->assign ( 'SID', SID );
		$mysmarty->assign ( 'SESSNAME', session_name() );
		$mysmarty->assign ( 'SESSID', session_id() );
		
		if (! SessionVarification::get_user ()) {
		
			$mysmarty->assign ( 'csssource', './stylesheets/user.css' );
			$mysmarty->display ( 'topunlogin.tpl' );
		} else {
			$loggeduser = SessionVarification::get_user ();
			$loggedlink = './user.php?username=' . $loggeduser . '&' . SID;
			$results = $mysql->read_operate ( 'select avatar from users WHERE username="' . $loggeduser . '"', 1 );
			if ($results [0] [0] != null) {
				$loggedavatar = $results [0] [0];
			} else {
				$loggedavatar = './defaultavatar.png';
			}
			$mysmarty->assign ( 'csssource', './stylesheets/user.css' );
			$mysmarty->assign ( 'loggeduser', $loggeduser );
			$mysmarty->assign ( 'loggedlink', $loggedlink );
			$mysmarty->assign ( 'loggedavatar', $loggedavatar );
			$mysmarty->display ( 'toplogin.tpl' );
		}
		
		//显示part1
		if ($username != SessionVarification::get_user()){
			$mysmarty->assign('username',$username);
			
			$results = $mysql->read_operate ( 'select avatar from users WHERE username="' . $username . '"', 1 );
			if ($results [0] [0] != null) {
				$avatar = $results [0] [0];
			} else {
				$avatar = './defaultavatar.png';
			}
			$mysmarty->assign('useravatar',$avatar);
			
			$results = $mysql->read_operate ( 'select sex from users WHERE username="' . $username . '"', 1 );
			if ($results [0] [0] == '0'){
				$sex = '性别：男';
			}
			elseif ($results [0] [0] == '1'){
				$sex = '性别：女';
			}
			else {$sex = '性别未知';}
			$mysmarty->assign('sex',$sex);
			
			if($totalcomments<=8){
				$mysmarty->assign('commentbutton','$(function(){$(".previouspage").addClass("class");$(".nextpage").addClass("class");});');
			}
			else {$mysmarty->assign('commentbutton','$(function(){$(".previouspage").addClass("class");});');}
			
			$mysmarty->display('userpart1.tpl');
			
		}
		else {//当访问的用户为自己
			$mysmarty->assign('username',$username);
				
			$results = $mysql->read_operate ( 'select avatar from users WHERE username="' . $username . '"', 1 );
			if ($results [0] [0] != null) {
				$avatar = $results [0] [0];
			} else {
				$avatar = './defaultavatar.png';
			}
			$mysmarty->assign('useravatar',$avatar);
				
			$results = $mysql->read_operate ( 'select sex from users WHERE username="' . $username . '"', 1 );
			if ($results [0] [0] == '0'){
				$sex = '性别：男';
			}
			elseif ($results [0] [0] == '1'){
				$sex = '性别：女';
			}
			else {$sex = '性别未知';}
			$mysmarty->assign('sex',$sex);
				
			if($totalcomments<=8){
				$mysmarty->assign('commentbutton','$(function(){$(".previouspage").addClass("class");$(".nextpage").addClass("class");});');
			}
			else {$mysmarty->assign('commentbutton','$(function(){$(".previouspage").addClass("class");});');}
				
			$mysmarty->display('userselfpart1.tpl');
				
		}
		
		
		//显示part2
		$results = $mysql->read_operate('select articleid,comment,ts from comments where userid="'.$userid.'" ORDER BY ts DESC LIMIT 8', 3);
		for($i=0;$i<count($results);$i++){
			$mysmarty->assign('commenttime',$results[$i][2]);
			$mysmarty->assign('comment',$results[$i][1]);
			$mysmarty->assign('articlelink','./article.php?articleid='.$results[$i][0].'&'.SID);
			$result= $mysql->read_operate('select title from articles where articleid="'.$results[$i][0].'"', 1);
			$mysmarty->assign('title',$result[0][0]);
			$mysmarty->display('userpart2.tpl');
		}
		
		//显示part3
		$mysmarty->assign('username',$username);
		$mysmarty->assign('totalpage',ceil($totalcomments/8));
		$mysmarty->display('userpart3.tpl');
		
		// 显示footer
		$mysmarty->display ( 'footer.tpl' );
	}
	$mysql->close();
}