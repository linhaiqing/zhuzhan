<?php
	return [

		// +----------------------------------------------------------------------
		// | 路径配置
		// +----------------------------------------------------------------------

		// 网站根目录路径
		'app_path'           => APP_PATH,
		// 网站备份目录路径
		'app_backup'         => APP_PATH . '/backup',
		// 网站配置目录路径
		'app_conf'           => APP_PATH . '/conf',
		// 控制器目录路径
		'app_controller'     => APP_PATH . '/controller',
		// 静态网页目录路径
		'app_htmls'          => APP_PATH . '/htmls',
		// 系统插件目录路径
		'app_plugin'         => APP_PATH . '/plugin',
		// 系统缓存目录路径
		'app_runtime'        => APP_PATH . '/runtime',
		// 静态资源目录路径
		'app_static'         => APP_PATH . '/static',
		// 模板视图目录路径
		'app_template'       => APP_PATH . '/template',
		// 文件上传目录路径
		'app_upload'         => APP_PATH . '/upload',

		// 数据库配置文件
		"db_conf"            => "db.php",

		// +----------------------------------------------------------------------
		// | 基础配置
		// +----------------------------------------------------------------------

		// 系统版本号
		'ms_version'         => '8.3.3',
		// 开启调试模式
		'ms_debug'           => 1,
		// 显示请求信息
		'ms_trace'           => 1,
		// 短信调试模式
		'ms_sms'             => 1,
		// 后台安全地址
		'ms_admin'           => '',
		// 定义访问域名
		'ms_host'            => 'a.com',
		// 系统授权密匙
		'ms_code'            => '95D3A7E98EE9F913B462B87C73DS',

		// +----------------------------------------------------------------------
		// | 应用设置
		// +----------------------------------------------------------------------

		// 允许访问的模块
		'module'             => ["home", "admin", "app", "api", "wap"],
		// 模块变量标识
		'model_tag'          => 'm',
		// 控制器变量标识
		'controller_tag'     => 'c',
		// 动作标识
		'action_tag'         => 'a',
		// 默认模块
		"default_model"      => 'home',
		// 默认的控制器名称
		"default_controller" => "index",
		// 默认的动作名称
		"default_action"     => "index",
		// 默认url后缀
		"default_ext"        => ".html",
		// 默认模板后缀
		"default_tem_ext"    => ".html",
		// 默认主题
		"default_template"   => 'default',
		// 默认语言
		'mr_lang'            => 'cn',

		// +----------------------------------------------------------------------
		// | 模块设置
		// +----------------------------------------------------------------------

		// 记录当前时间
		"move_time"          => "",
		// 记录当前内存
		"move_mem"           => "",
		// 记录当前模型
		"module_name"        => "",
		// 记录当前控制器
		"controller_name"    => "",
		// 记录当前方法
		"action_name"        => "",

		// +----------------------------------------------------------------------
		// | cookie设置
		// +----------------------------------------------------------------------

		'cookie' => [
			// cookie 名称前缀
			'prefix'    => 'movesay',
			// cookie 保存时间
			'expire'    => 0,
			// cookie 保存路径
			'path'      => '/',
			// cookie 有效域名
			'domain'    => '',
			// cookie 启用安全传输
			'secure'    => false,
			// httponly设置
			'httponly'  => '',
			// 是否使用 setcookie
			'setcookie' => true,
		],

		// +----------------------------------------------------------------------
		// | session设置
		// +----------------------------------------------------------------------

		'session'     => [
			'id'             => '',
			// SESSION_ID的提交变量,解决flash上传跨域
			'var_session_id' => '',
			// SESSION 前缀
			'prefix'         => 'movesay',
			// 驱动方式 支持redis memcache memcached
			'type'           => 'memcached',
			// 是否自动开启 SESSION
			'auto_start'     => true,
			//
			'httponly'       => true,
			//
			'secure'         => false,
		],

		// +----------------------------------------------------------------------
		// | 模板设置
		// +----------------------------------------------------------------------

		// 模板解析
		"static_path" => [
			"admin" => [
				"__ADMIN__MR__" => APP_STATIC . "/default/admin",
				"__ADMIN__"     => APP_STATIC . "/" . MS_TEMPLATE . "/admin",
				"__JS__"        => APP_STATIC . "/" . MS_TEMPLATE . "/admin/js",
				"__CSS__"       => APP_STATIC . "/" . MS_TEMPLATE . "/admin/css",
				"__IMG__"       => APP_STATIC . "/" . MS_TEMPLATE . "/admin/img",
				"__EXT__"       => APP_STATIC . "/extend",
				"__UP__"        => APP_UPLOAD,
			],
			"home"  => [
				"__HOME__MR__" => APP_STATIC . "/default/home",
				"__HOME__"     => APP_STATIC . "/" . MS_TEMPLATE . "/home",
				"__JS__"       => APP_STATIC . "/" . MS_TEMPLATE . "/home/js",
				"__CSS__"      => APP_STATIC . "/" . MS_TEMPLATE . "/home/css",
				"__IMG__"      => APP_STATIC . "/" . MS_TEMPLATE . "/home/img",
				"__EXT__"      => APP_STATIC . "/extend",
				"__UP__"       => APP_UPLOAD,
			],
			"wap"   => [
				"__WAP__MR__" => APP_STATIC . "/default/wap",
				"__WAP__"     => APP_STATIC . "/" . MS_TEMPLATE . "/wap",
				"__JS__"      => APP_STATIC . "/" . MS_TEMPLATE . "/wap/js",
				"__CSS__"     => APP_STATIC . "/" . MS_TEMPLATE . "/wap/css",
				"__IMG__"     => APP_STATIC . "/" . MS_TEMPLATE . "/wap/img",
				"__EXT__"     => APP_STATIC . "/extend",
				"__UP__"      => APP_UPLOAD,
			],
			"api"   => [
				"__API__MR__" => APP_STATIC . "/default/api",
				"__API__"     => APP_STATIC . "/" . MS_TEMPLATE . "/api",
				"__JS__"      => APP_STATIC . "/" . MS_TEMPLATE . "/api/js",
				"__CSS__"     => APP_STATIC . "/" . MS_TEMPLATE . "/api/css",
				"__IMG__"     => APP_STATIC . "/" . MS_TEMPLATE . "/api/img",
				"__EXT__"     => APP_STATIC . "/extend",
				"__UP__"      => APP_UPLOAD,
			],

		],
		"template"    => [
			"filter"      => [
				"status"   => 1, // 过滤状态  默认0
				"wrap"     => 1, // 保留换行符 默认1
				"wrap_com" => 0, // 多个换行符合并为一个
			],
			"script_move" => 0,// script标签下移 默认 1
		],

		// +----------------------------------------------------------------------
		// | 缓存设置
		// +----------------------------------------------------------------------

		// 缓存类型
		"cache_type"  => "memcache",

		// +----------------------------------------------------------------------
		// | 上传设置
		// +----------------------------------------------------------------------

		"upload"   => [
			"type" => "file",// file 和 oss 模式
			"oss"  => [
				"host"            => "",    // 需要结尾 /
				"OSS_ACCESS_ID"   => "",
				"OSS_ACCESS_KEY"  => "",
				"OSS_ENDPOINT"    => "",
				"OSS_TEST_BUCKET" => "",
			],
		],

		// +----------------------------------------------------------------------
		// | 其他设置
		// +----------------------------------------------------------------------

		// 开启httponly 防止xss攻击 默认开启
		"httponly" => 1,
		// 开启GZIP压缩 减少数据传输 默认开启
		"gzip"     => 0,

	];
