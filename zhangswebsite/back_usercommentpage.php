<?php
include_once 'class/class.MySmarty.php';
include_once 'class/class.MysqlTools.php';

if (isset ( $_POST ['page'] ) && isset ( $_POST ['username'] )) { // 必须传递用户名和页面编号才可访问
	
	echo "["; // json方括号表示数组
	
	$mysql = new MysqlTool ();
	$mysql->connect () or die ( '服务器维护中' );
	
	$result1 = $mysql->read_operate ( 'select userid from users where username="' . $_POST ['username'] . '"', 1 );
	$result2 = $mysql->read_operate ( 'select COUNT(*) from comments where userid="' . $result1 [0] [0] . '"', 1 );
	$totalcomments = $result2 [0] [0]; // 评论总数
	
	if ($totalcomments > 0 && $_POST ['page'] >= 1 && $_POST ['page'] <= ceil ( $totalcomments / 8 )) {
		$page = $_POST ['page'];
		$result2 = $mysql->read_operate('select articleid,comment,ts from comments where userid="'.$result1 [0] [0].'" ORDER BY ts DESC LIMIT ' . (($page - 1) * 8 > 0 ? ($page - 1) * 8 : '') . (($page - 1) * 8 > 0 ? ',8' : '8'), 3);
		for($i=0;$i<count($result2);$i++){
			$result1 = $mysql->read_operate('select title from articles where articleid="'.$result2[$i][0].'"', 1);
			echo "{'commenttime':'".$result2[$i][2]."',";
			echo "'articlelink':'".'./article.php?articleid='.$result2[$i][0].'&'.SID."',";
			echo "'title':'".$result1[0][0]."',";
			echo "'comment':'".$result2[$i][1]."'}";
			if($i<count($result2)-1){
				echo ",";
			}
		}
	}
	
	echo "]"; // 方括号表示数组
	
	$mysql->close ();
} else {
	header ( "Location:./index.php?" . SID );
}