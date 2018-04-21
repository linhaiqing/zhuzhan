<?php


//if (PHP_SAPI != 'cli') {
//		if(!isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] != "www.cdae.vip" || $_SERVER['HTTP_HOST'] != "jz.cdae.vip"|| $_SERVER['HTTP_HOST'] != "cdae.vip"){
//			header('Location:http://jz.cdae.vip');
//			exit();
//		}
//	}
	
	
	//防止XSS攻击
	ini_set("session.cookie_httponly", 1);
	// 定义系统编码
	header("Content-Type: text/html;charset=utf-8");
	// 定义应用路径
	define("APP_PATH", dirname(__FILE__));
	// 定义上传路径
	define('APP_UPLOAD', "/upload/");
	// 定义资源路径
	define('APP_STATIC', "/static");
	// 定义数据库地址
	 define('DB_HOST', '127.0.0.1');
	 // 定义数据库库名
	 define('DB_NAME', 'jy');
	// 定义数据库账户
	 define('DB_USER', 'root');
	 // 定义数据库密码
	 define('DB_PASS', 'fg5hFD5Sfd5g45');
	// 实现后台能够替换模板
	$template = file_get_contents(APP_PATH . '/backup/template.ini');
	if (!$template) {
		$template = 'default';
	}
	if (isset($_GET['template'])) {
		$template = $_GET['template'];
	}
	// 定义系统模板
	define('MS_TEMPLATE',$template);
	// 开启调试模式
	define('MS_DEBUG', 1);
	define('MAIL_SSL', 1);

	// 后台安全地址
	define('ADMIN_KEY', 'sadhmfvbuhwefbkjku6h7h');
	// 定义访问域名
	define('APP_HOST', $_SERVER['HTTP_HOST']);
	// 系统授权密匙
	define('MS_CODE', "95D3A7E98EE9F913B462B87C73DS");

	// 引入入口文件mo
	Move\mvc::run();
