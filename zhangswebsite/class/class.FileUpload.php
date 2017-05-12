<?php
include_once 'initialization.php';
/**
 * @name文件上传类
 * @author章望成
 * @tutorial 文件最大大小 文件名 文件路径
 */
class FileUpload{
	/**
	 * 上传文件到指定位置<br>
	 * $path最后必须有斜杠/，$name为文件名<br>
	 * 成功返回true 失败返回false
	 * @access public
	 * @param string $path
	 * @param string $name
	 * @return bool
	 */
	public static $filename;
	
	public static function uploads_file($path,$name){
		if($_FILES[self::$filename]['error'] == UPLOAD_ERR_OK){
			return move_uploaded_file($_FILES[self::$filename]['tmp_name'], $path.$name);
		}
		return false;
	}
	
	
	/**
	 * 返回原文件名
	 * @return string
	 */
	public static function get_filename(){
		return $_FILES[self::$filename]['name'];//原文件名
	}
	
	
	/**
	 * 返回文件大小（字节）
	 * @return int
	 */
	public static function get_filesize(){
		return $_FILES[self::$filename]['size'];//单位为字节
	}
}