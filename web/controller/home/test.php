<?php
namespace home;
use Move\db;

class test extends home
{
	// 初始化
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index(){
	
	  $email = iv('get.email','email','请输入邮箱');
		// 获取邮箱信息
		$sys_email = db::table('sys_email')->select();

		$email_config = [];
		foreach($sys_email as $k=>$v){
			$email_config[$v['name']] =  $v['value'];
		}

		$addr = ['email'=>$email,'name'=>'',"title"=>'test111',"content"=>'这里是测试内容'];
		$email = new \ext\email();
		$res = $email->sendemail($addr,$email_config);
		dump($res);
	}

}