<?php
/*
 * mysql初始化
 */
const MYSQL_USERNAME = "root"; // mysql用户名
const MYSQL_PASSWORD = "chengwangzhang"; // mysql密码
const DATABASE_NAME = 'zhangswebsite';
const HOSTNAME = 'localhost';
/*
 * memcached初始化
 */
const MEMCACHED_HOST = 'localhost'; // 主机名
const MEMCACHED_PORT = 11211; // 端口号
/*
 * Smarty初始化
 */
$root_path = dirname ( __FILE__ ); // 根目录名
const TEMPLATES_PATH = "/templates/"; // 模版文件目录
const TEMPLATES_C_PATH = "/templates_compiled/"; // 模版编译文件目录
const TMP_PLUGINS_PATH = "/smartyplugins/"; // 模版插件文件目录
const TMP_CACHE_PATH = "/smartycache/"; // 模版缓存文件目录
const TMP_CONFIG = "/smartyconfigs"; // 模版配置文件位置
const SMARTY_CACHING = false; // 模版缓存开关功能
const SMARTY_CACHE_LIFETIME = 86400; // 模版缓存最长有效时间1天
const LEFT_DELIMITER = '<{'; // 模版左限定符
const RIGHT_DELIMITER = '}>'; // 模版右限定符
                              
// 主机ip
const MY_IP = '120.24.171.4';

// 开启session

if (isset( $_GET [session_name ()] ) ) // 判断链接后是否传递了session的值
{
	$sid = $_GET [session_name ()] ;
	session_id ( $sid ); // 不为空则用已有的sid开启会话
}
elseif (isset( $_POST [session_name ()] )){
	$sid = $_POST [session_name ()] ;
	session_id ( $sid ); // 不为空则用已有的sid开启会话
}
session_start ();

//禁用缓存
header("Expires:-1");
header("Cache-Control:no_cache");
header("Pragma:no-cache");