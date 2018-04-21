<?php
namespace model;

use Move\db;

class links extends common
{
	public function index_lists($size = 20)
	{
		return db::table("sys_link")->where(["status" => 1])->order("sort asc")->limit($size)->select();
	}
}