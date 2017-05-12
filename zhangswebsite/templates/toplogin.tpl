<!doctype html>
<html>
<head>
<base target="_self"/>
<meta charset="utf-8">
<title>zhang's website</title>
<link href="<{$csssource}>" rel="stylesheet" type="text/css">
</head>

<body>
<header class="topmargin">
  <h1><a href="./?<{$SID}>">ZHANG'S WEBSITE!</a></h1>
  <form action="./search.php" method="get" class="search">
    <input name="search" type="text" id="search" placeholder="请输入搜索内容">
    <button type="submit">搜索</button>
    <input type="hidden" name="<{$SESSNAME}>" value="<{$SESSID}>">
  </form>
  <div class="loggeduser"><img src="<{$loggedavatar}>" width="40" height="40"><a href="<{$loggedlink}>" class="username"><{$loggeduser}></a><a href="javascript:;" onclick="if(window.confirm('确实要登出吗？')){window.location.href='./logout.php';}" class="logout">登出</a></div>
</header>