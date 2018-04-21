<?php
namespace model;

use Move\db;

class game extends common
{
	public function lists()
	{
		$res = db::table("version_game")->where(["status" => 1])->order("`sort` ASC")->select();
		if (!is_array($res)) {
			return false;
		}
		foreach ($res as $key => $val) {
			$data[$key] = $val;
		}
		return $data;
	}
}