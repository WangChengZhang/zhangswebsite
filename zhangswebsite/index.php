<?php
include_once 'class/class.MySmarty.php';
include_once 'class/class.MysqlTools.php';
include_once 'class/class.SessionVarification.php';

$page = 1;
if (isset ( $_GET ['page'] )) {
	$page = $_GET ['page'];
}
$mysmarty = new MySmarty ();
$mysmarty->assign ( 'SID', SID );
$mysmarty->assign ( 'SESSNAME', session_name() );
$mysmarty->assign ( 'SESSID', session_id() );

$mysql = new MysqlTool ();
$mysql->connect () or die ( '服务器维护中' );

// 显示头部
if (! SessionVarification::get_user ()) {
	
	$mysmarty->assign ( 'csssource', './stylesheets/index.css' );
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
	$mysmarty->assign ( 'csssource', './stylesheets/index.css' );
	$mysmarty->assign ( 'loggeduser', $loggeduser );
	$mysmarty->assign ( 'loggedlink', $loggedlink );
	$mysmarty->assign ( 'loggedavatar', $loggedavatar );
	$mysmarty->display ( 'toplogin.tpl' );
}

// 显示part1
$comment = array ();
$user = array ();
$avatar = array ();
$linktitle = array ();
$artitle = array ();

$arecommend = array ();
$trecommend = array ();
$recomimg = array ();

$innerresults = array ();

$results = $mysql->read_operate ( 'select articleid,userid,comment from comments ORDER BY ts DESC LIMIT 5', 3 );
for($i = 0; $i < 5; $i ++) {
	$comment [] = $results [$i] [2];
	$innerresults = $mysql->read_operate ( "select username,avatar from users WHERE userid=" . $results [$i] [1], 2 );
	$user [] = "./user.php?username=" . $innerresults [0] [0] . '&' . SID;
	if ($innerresults [0] [1] != NULL) {
		$avatar [] = $innerresults [0] [1];
	} else {
		$avatar [] = './defaultavatar.png';
	}
	$innerresults = $mysql->read_operate ( "select title from articles WHERE articleid=" . $results [$i] [0], 1 );
	$artitle [] = substr ( $innerresults [0] [0], 0, 20 );
	$linktitle [] = "./article.php?articleid=" . $results [$i] [0] . '&' . SID;
}

$results = $mysql->read_operate ( "select SQL_CALC_FOUND_ROWS articleid,title,cover from articles ORDER BY clicknum DESC LIMIT 3", 3 );
for($i = 0; $i < 3; $i ++) {
	$arecommend [] = "./article.php?articleid=" . $results [$i] [0] . '&' . SID;
	$trecommend [] = $results [$i] [1];
	if ($results [$i] [2] != NULL) {
		$recomimg [] = $results [$i] [2];
	} else {
		$recomimg [] = "./defaultcover.jpg";
	}
}

$mysmarty->assign ( 'comment', $comment );
$mysmarty->assign ( 'user', $user );
$mysmarty->assign ( 'avatar', $avatar );
$mysmarty->assign ( 'artitle', $artitle );
$mysmarty->assign ( 'linktitle', $linktitle );
$mysmarty->assign ( 'arecommend', $arecommend );
$mysmarty->assign ( 'trecommend', $trecommend );
$mysmarty->assign ( 'recomimg', $recomimg );
$mysmarty->display ( 'indexpart1.tpl' );

// 显示part3
$articlelink = '';
$articletitle = '';
$author = '';
$authorname = '';
$posttime = '';
$cover = '';
$abstract = '';
$commentnum = '';
$clicknum = '';
$sourcelink = '';
$source = '';

$sum_of_articles = $mysql->read_operate ( 'select FOUND_ROWS()', 1 ) [0] [0];
$sum_of_pages = ceil ( $sum_of_articles / 8 );
if ($page < 1 || $page > $sum_of_pages) {
	$page = 1;
}
$results = $mysql->read_operate ( 'select articleid,userid,title,cover,left(articlehtml,200),pubtime,source,clicknum,commentnum from articles ORDER BY pubtime DESC LIMIT ' . (($page - 1) * 8 > 0 ? ($page - 1) * 8 : '') . (($page - 1) * 8 > 0 ? ',8' : '8'), 9 );
$matches = array ();
for($i = 0; $i < count ( $results ); $i ++) {
	$articlelink = './article.php?articleid=' . $results [$i] [0] . '&' . SID;
	$articletitle = $results [$i] [2];
	$authorname = $mysql->read_operate ( 'select username from users where userid=' . $results [$i] [1], 1 ) [0] [0];
	$author = './user.php?username=' . $authorname . '&' . SID;
	$posttime = $results [$i] [5];
	if ($results [$i] [3] != NULL) {
		$cover = $results [$i] [3];
	} else {
		$cover = "./defaultcover.jpg";
	}
	$abstract = strip_tags ( $results [$i] [4] ); // 去除html和php标记
	$commentnum = $results [$i] [8];
	$clicknum = $results [$i] [7];
	if ($results [$i] [6] != NULL) {
		preg_match ( '/(.+)\|/', $results [$i] [6], $matches );
		$source = $matches [1];
		preg_match ( '/\|(.+)/', $results [$i] [6], $matches );
		$sourcelink = $matches [1];
	}
	$mysmarty->assign ( 'articlelink', $articlelink );
	$mysmarty->assign ( 'articletitle', $articletitle );
	$mysmarty->assign ( 'author', $author );
	$mysmarty->assign ( 'authorname', $authorname );
	$mysmarty->assign ( 'posttime', $posttime );
	$mysmarty->assign ( 'cover', $cover );
	$mysmarty->assign ( 'abstract', $abstract );
	$mysmarty->assign ( 'commentnum', $commentnum );
	$mysmarty->assign ( 'clicknum', $clicknum );
	$mysmarty->assign ( 'sourcelink', $sourcelink );
	$mysmarty->assign ( 'source', $source );
	
	$mysmarty->display ( 'indexpart2section.tpl' );
}
if (count ( $results ) < 4) {
	$mysmarty->display ( 'indexpart2placeholder.tpl' ); // 数目过少占点空位
}

// 显示part4
if ($page == 1 && $sum_of_pages > 1) {
	$mysmarty->assign ( 'older', './index.php?page=2' . '&' . SID );
	$mysmarty->display ( 'indexpart3a.tpl' );
} elseif ($page == $sum_of_pages && $sum_of_pages > 1) {
	$mysmarty->assign ( 'newer', './index.php?page=' . ($page - 1) . '&' . SID );
	$mysmarty->display ( 'indexpart3c.tpl' );
} elseif ($sum_of_pages > $page && $page > 1) {
	$mysmarty->assign ( 'older', './index.php?page=' . ($page + 1) . '&' . SID );
	$mysmarty->assign ( 'newer', './index.php?page=' . ($page - 1) . '&' . SID );
	$mysmarty->display ( 'indexpart3b.tpl' );
}
// 显示footer
$mysmarty->display ( 'footer.tpl' );


$mysql->close();


