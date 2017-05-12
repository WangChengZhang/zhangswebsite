<?php 
include_once 'initialization.php';
/**
 * @name身份验证类
 * @author章望成
 * @tutorial通过该类向session和memcached操作身份信息或者读取身份信息
 */
class SessionVarification{
	//memcached对象
	static private $memcached;
	
	//memcached创建函数 成功返回true失败false
	static private function create_memcached(){
		if(!self::$memcached){//不存在则创建
			self::$memcached =new Memcached();
			return self::$memcached->addServer(MEMCACHED_HOST,MEMCACHED_PORT);
		}
	}
	
	
	/**
	 * 向memcached和session存储用户名
	 * @access public
	 * @param string $username
	 */
	static public function set_user($username){
		self::create_memcached();//创建memcached对象
		$_SESSION['username'] = $username;//向session中存储用户名
		self::$memcached->set(session_id(),$username,1800);//memcached中用sid作键，用户名作值，有效期1800秒
	}
	
	
	/**
	 * 查询memcached或session是否存储有用户<br/>
	 * 存在则返回用户名，其它返回false
	 * @access public
	 * @param void
	 * @return mixed(string or bool)
	 */
	static public function get_user(){
		self::create_memcached();//创建memcached对象
		if (self::$memcached->get(session_id()))//默认从memcached读取
		{
			return self::$memcached->get(session_id());
		}
		if(!array_key_exists('username', $_SESSION))
		{
			return false;
		}
		return $_SESSION['username'];
	}
	
	
	/**
	 * 销毁数据
	 * @access public
	 * @param void
	 */
	static public function destroy(){
		self::create_memcached();//创建memcached对象
		self::$memcached->delete(session_id());//删除memcached中的数据
		$_SESSION = array();//清空session
		if(isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(),'',time()-3600,'/');//删除浏览器cookie保存到session数据
		}
		session_destroy();//销毁session
		self::$memcached->quit();
	}
}