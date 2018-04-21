<?php
	return [
		// 数据库类型
		'db_type' => 'mysql',
		// 数据库读写是否分离 主从式有效
		'db_rw'   => false,
		// 服务器地址
		"db_host" => DB_HOST,
		// 数据库名
		"db_name" => DB_NAME,
		// 数据库连接端口
		"db_port" => '3306',
		// 数据库用户名
		"db_user" => DB_USER,
		// 数据库密码
		"db_pass" => DB_PASS,
		// 数据库超时
		"db_timeout" => '5',

	    'db_rw_arr'=>[

				[
					// 服务器地址
					"db_host" => DB_HOST,
					// 数据库名
					"db_name" => DB_NAME,
					// 数据库用户名
					"db_user" => DB_USER,
					// 数据库密码
					"db_pass" => DB_PASS,
				],

	    ],

	];