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
  <h2><a href="./login.php">登录</a></h2>
  <h3><a href="./signin.php">注册</a></h3>
</header>
