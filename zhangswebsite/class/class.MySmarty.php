<?php
include_once "initialization.php";
include_once "smartylibs/Smarty.class.php";

/**
 * @name继承自Smarty类
 * @author章望成
*/
class MySmarty extends Smarty{
	/**
	 * 只继承父类的构造方法并运行
	 */
	function __construct(){
		global $root_path;
		parent::__construct();
		$this->setTemplateDir($root_path.TEMPLATES_PATH);
		$this->setCompileDir($root_path.TEMPLATES_C_PATH);
		$this->setPluginsDir($root_path.TMP_PLUGINS_PATH);
		$this->setCacheDir($root_path.TMP_CACHE_PATH);
		$this->setConfigDir($root_path.TMP_CONFIG);
		$this->caching = SMARTY_CACHING;
		$this->cache_lifetime = SMARTY_CACHE_LIFETIME;
		$this->left_delimiter = LEFT_DELIMITER;
		$this->right_delimiter = RIGHT_DELIMITER;
	}
}
