<?php
namespace model;

use Move\db;

class sys_ad extends common
{
	public function index_lists()
	{
		$res = db::table("sys_ad")->where(["status" => 1])->order("`sort` ASC")->limit(10)->select();
		if (!is_array($res)) {
			return false;
		}
		foreach ($res as $key => $val) {
			$data[$key] = $val;
		}
		return $data;
	}
}