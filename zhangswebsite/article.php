<?php
include_once 'class/class.MySmarty.php';
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

$mysql = new MysqlTool ();
$mysql->connect () or die ( '服务器维护中' );

if (isset ( $_GET ['articleid'] ) && preg_match ( '/[0-9]*/', $_GET ['articleid'] ) && $mysql->read_operate ( 'select articleid from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) != null) {
	
	$mysmarty = new MySmarty ();
	$mysmarty->assign ( 'SID', SID );
	$mysmarty->assign ( 'SESSNAME', session_name () );
	$mysmarty->assign ( 'SESSID', session_id () );
	
	// 显示头部
	if (! SessionVarification::get_user ()) {
		
		$mysmarty->assign ( 'csssource', './stylesheets/article.css' );
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
		$mysmarty->assign ( 'csssource', './stylesheets/article.css' );
		$mysmarty->assign ( 'loggeduser', $loggeduser );
		$mysmarty->assign ( 'loggedlink', $loggedlink );
		$mysmarty->assign ( 'loggedavatar', $loggedavatar );
		$mysmarty->display ( 'toplogin.tpl' );
	}
	
	// 显示part1
	$title = $mysql->read_operate ( 'select title from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) [0] [0];
	$result = $mysql->read_operate ( 'select userid from articles where articleid="' . $_GET ['articleid'] . '"', 1 );
	$author = $mysql->read_operate ( 'select username from users where userid="' . $result [0] [0] . '"', 1 ) [0] [0];
	$authorlink = './user.php?username=' . $author . '&' . SID;
	$date = $mysql->read_operate ( 'select pubtime from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) [0] [0];
	$click = $mysql->read_operate ( 'select clicknum from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) [0] [0];
	$commentsnum = $mysql->read_operate ( 'select commentnum from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) [0] [0];
	$article = $mysql->read_operate ( 'select articlehtml from articles where articleid="' . $_GET ['articleid'] . '"', 1 ) [0] [0];
	$matches = array ();
	$result = $mysql->read_operate ( 'select source from articles where articleid="' . $_GET ['articleid'] . '"', 1 );
	$source = '';
	$sourcelink = '';
	if ($result [0] [0] != NULL) {
		preg_match ( '/(.+)\|/', $result [0] [0], $matches );
		$source = $matches [1];
		preg_match ( '/\|(.+)/', $result [0] [0], $matches );
		$sourcelink = $matches [1];
	}
	$mysmarty->assign ( 'title', $title );
	$mysmarty->assign ( 'author', $author );
	$mysmarty->assign ( 'authorlink', $authorlink );
	$mysmarty->assign ( 'date', $date );
	$mysmarty->assign ( 'click', '点击数:'.$click );
	$mysmarty->assign ( 'commentsnum', '评论数:'.$commentsnum );
	$mysmarty->assign ( 'article', $article );
	$mysmarty->assign ( 'source', $source );
	$mysmarty->assign ( 'sourcelink', $sourcelink );
	$mysmarty->display('articlepart1.tpl');
	
	//显示part2
	$result = $mysql->read_operate('select userid,comment,ts from comments where articleid="'. $_GET ['articleid'] . '" ORDER BY ts ', 3);
	if ($result!=null){
		for ($i=0;$i<count($result);$i++){
			$results = $mysql->read_operate('select avatar,username from users where userid="'.$result[$i][0].'"', 2);
			$commentuser = $results[0][1];
			$commentuserlink = './user.php?username='.$commentuser.'&'.SID;
			if ($results[0][0]!=null){
				$avatar = $results[0][0];
			}
			else {
				$avatar = './defaultavatar.png';
			}
			$comment = $result[$i][1];
			$commenttime = $result[$i][2];
			$mysmarty->assign ( 'commentuser',$commentuser );
			$mysmarty->assign ( 'commentuserlink',$commentuserlink );
			$mysmarty->assign ( 'avatar',$avatar );
			$mysmarty->assign ( 'comment',$comment );
			$mysmarty->assign ( 'commenttime',$commenttime );
			$mysmarty->display('articlepart2.tpl');
		}
	}
	//显示part3
	if (! SessionVarification::get_user ()){
		$mysmarty->display('articlepart3b.tpl');
	}
	else {
		$mysmarty->assign('articleid',$_GET['articleid']);
		$mysmarty->display('articlepart3a.tpl');
	}
	
	// 显示footer
	$mysmarty->display ( 'footer.tpl' );
	
	//点击数增加
	$mysql->cdu_operate('update articles set clicknum=clicknum+1 where articleid="' . $_GET ['articleid'] . '"');
	$mysql->commit();
	
	
} else {
	header ( "Location:./index.php?" . SID ); // 没传入文章id不能访问
}

$mysql->close ();

