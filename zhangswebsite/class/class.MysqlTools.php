<?php
include_once 'initialization.php';
/**
 *@name 自制mysql增删改查工具，使用MySQLi的预处理技术
 *@author章望成
 *@tutorial使用方法为<br/>
 *1、创建$mysqltool对象<br/>
 *2、建立连接$mysqltool->connect()<br/>
 *3、执行操作$mysqltool->cdu_operate()或者$mysqltool->read_operate()<br/>同一mysqltool对象可执行多次<br/>
 *4、如果要取消操作可$mysqltool->rollback(),确认无误可以提交$mysqltool->commit()(查操作无需执行此项)<br/>
 *5、关闭连接$mysqltool->close()
 * */
class MysqlTool{
	//mysqli对象
	private $mysqli;
	
	
	
	/**
	 * 连接数据库并创建mysqli对象<br/>
	 *成功返回true，失败返回false
	 *@access public
	 *@param void
	 *@return bool
	 */
	public function connect(){
		$this->mysqli = new mysqli(HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD,DATABASE_NAME);
		if($this->mysqli->connect_error||(!$this->mysqli->autocommit(false))/*设置不自动提交好启用回滚机制*/)
		{
			return false;
		}
		return true;
	}
	
	
	
	/**
	*增删改操作
	*参数(sql语句[，$参数1，str参数1的类型，$参数2，参数2的类型，$参数3...])<br/>
	*sql语句中要绑定的参数用?代替<br/>
	*参数类型'i'int类型 'd'浮点类型 's'字符串类型 'b'二进制类型<br/>
	*失败返回false，成功返回1，没有影响到行返回-1
	*@access public
	*@param string $sql
	*@param mixed $param1
	*@param string $p1type
	*@return mixed
	*/
	public function cdu_operate($sql){
		/*-----------------------创建预处理对象---------------------*/
		$mysqli_stmt=$this->mysqli->prepare($sql);
		/*--------------------------绑定参数-------------------*/
		//算出类型字符串
		$types = '';//类型
		$args = [];//参数
		if(func_num_args()>1)//如果参数大于一个就绑定参数
		{
			//根据参数的类型在func_get_args()的第3，5，7...位
			//算出参数，根据参数在func_get_args()的第2，4，6...位使
			for($i=2;$i<func_num_args();$i+=2){
				$args[]=func_get_arg($i-1);
				$types=$types.func_get_arg($i);
			}
			//用php5.6的新功能可变参函数的参数解包
			if(!($mysqli_stmt->bind_param($types, ...$args)))//绑定参数
			{
				$mysqli_stmt->close();/*关闭预编译对象*/
				return false;/*参数绑定失败*/
			}
		}
		/*-------------------------执行-------------------------*/
		if(!$mysqli_stmt->execute())
		{
			$mysqli_stmt->close();/*关闭预编译对象*/
			return false;/*执行失败*/
		}
		if ($mysqli_stmt->affected_rows>0)
		{
			$mysqli_stmt->close();/*关闭预编译对象*/
			return 1;/*成功*/
		}
		else 
		{
			$mysqli_stmt->close();/*关闭预编译对象*/
			return -1;/*没有行受到影响*/
		}
	}
	
	
	
	/**
	 * 查操作(绑定结果方式)<br/>
	 * 参数(sql语句,需要返回结果的列数)<br/>
	 * 失败返回false 无结果返回empty数组 成功返回一个二维数组$results，其中$results[0]表示第一行数据，$results[0][0]表示第一行第一列数据
	 *@access public
	 *@param string $sql
	 *@param int $column_num
	 *@return mixed
	 * */
	public function read_operate($sql,$column_num){
		/*------------------------创建预处理对象----------------------*/
		$mysqli_stmt=$this->mysqli->prepare($sql);

		$results = [];//所有结果的二维数组
		for($i=0;$i<$column_num;$i++)
		{
			$result[] = '';//单行结果的数组引用
		}
		$result2=[];//单行的结果数组，非引用
		/*-------------------------执行--------------------------------*/
		if(!$mysqli_stmt->execute())
		{
			echo 'fail2';
			echo $mysqli_stmt->error;
			$mysqli_stmt->close();/*关闭预编译对象*/
			return false;/*执行失败*/
		}
		/*-----------------------绑定结果集-------------------------------*/
		//注意，php5.5函数引用已经不允许加&了，而该函数不支持引用调用，故不能使用call_user_func_array(array($mysqli_stmt,'bind_result'), &$result);
		if(!$mysqli_stmt->bind_result(...$result))
		{
			$mysqli_stmt->close();/*关闭预编译对象*/
			return false;/*结果集绑定失败*/
		}
		/*--------取出结果集复制给$results数组-------------*/
		while($mysqli_stmt->fetch())
		{
			for($i=0;$i<$column_num;$i++)
			{
				$result2[$i] = $result[$i];//此举目的只为只得到引用的值，否则所有的results都将相同
			}
			$results[] = $result2;
		}
		$mysqli_stmt->free_result();/*释放结果集*/
		$mysqli_stmt->close();/*关闭预编译对象*/
		return $results;
	}
	
	
	
	
	/**
	 * 回滚函数，需要撤销时调用,对于已经commit的结果不能回滚<br/>
	 * 成功返回true 失败返回false
	 * @access public
	 * @param void
	 * @return bool
	 * */
	public function rollback(){
		if($this->mysqli->rollback())
		{return true;}
		return false;
	}
	
	
	
	/**
	 * 提交函数，确认提交结果<br/>
	 * 成功返回true 失败返回false
	 * @access public
	 * @param void
	 * @return bool
	 * */
	public function commit(){
		if($this->mysqli->commit())
		{return true;}
		return false;
	}
	
	
	
	/**
	 * 关闭mysqli对象
	 * */
	public function close(){
		$this->mysqli->close();
	}
}