<?phpnamespace model;use Move\db;class user_bank extends common{	public function lists($uid = NULL)	{		if ($uid === NULL) {			return db::table("user_bank")->select();		} else {			return db::table("user_bank")->where(["userid" => $uid])->select();		}	}	public function add($data)	{		if (db::table("user_bank")->add($data)) {			return [1, "操作成功"];		} else {			return [0, "操作失败"];		}	}	public function del($id)	{		if (!db::table("user_bank")->where(["id" => $id])->find()) {			return [0, "删除失败,id不存在"];		}		if (db::table("user_bank")->where(["id" => $id])->delete()) {			return [1, "删除成功"];		} else {			return [0, "删除失败"];		}	}}