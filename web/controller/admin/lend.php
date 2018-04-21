<?phpnamespace admin;use Move\db;class lend extends admin{	public function __construct()	{		parent::__construct();		if (!$this->is_game('lend')) {			ajax('这个应用你没有购买');		}	}	public function index()	{		$builder = new builder("lend");		$builder->title('借款管理', '这里是借款管理', '借款列表', '/lend/index')->button('no', '禁用', '/lend/status/type/0')->button('del', '删除', '/lend/status/type/-1')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', [0 => '全部状态', 1 => '禁用', 2 => '已完成', 3 => '待担保审核', 4 => '待系统审核', 5 => '审核通过', 6 => '到期未还清'], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('userid', '借款人', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('moble', '手机', 'text')->key('danbaoid', '担保人', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'danbaoid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'danbaoid', 'color' => '',]])->key('num', '借款数', 'text')->key('backtype', '还款类型', 'select', [1 => '', 2 => '', 3 => ''])->key('qi', '分期', 'text')->key('nowqi', '当前期', 'text')->key('lixi', '利率%', 'text')->key('shen', '剩余本金', 'text')->key('addtime', '申请时间', 'time')->key('endtime', '审批时间', 'time')->key('status', '状态', 'select', [0 => '禁用', 1 => '已完成', 2 => '待担保审核', 3 => '待系统审核', 4 => '审核通过', 5 => '到期未还清'])->key("ext", '操作', 'link', [['type' => 'button2', 'url' => '/lend/check/id/###', 'title' => '同意', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [3]]]])->lists();	}	public function status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'lend'));	}	public function check()	{		$id = iv("get.id", "d", "参数错误");		$res = md('lend')->check($id);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function lend_back()	{		$builder = new builder("lend_back");		$builder->title('还款账单', '这里是还款账单', '还款账单', '/lend/lend_back')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '禁用', '2' => '未还', '3' => '已还', '4' => "超期"], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('lendid', '借款ID', 'link', [['type' => 'button', 'url' => '/lend/index/id/###', 'title' => ['from_id' => 'lendid', 'table' => 'lend', 'to_id' => 'id', 'title' => 'id'], 'field' => 'userid', 'color' => '',]])->key('qi', '期', 'text')->key('should', '本期应还', 'text')->key('danbaofee', '担保收益', 'text')->key('endtime', '还款时间', 'time')->key('backtime', '还款截止时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">未还</span>', 2 => '<span style="color:#3498db;">已还</span>', 3 => '<span style="color:red;">超期</span>'])->lists();	}	public function lend_danbao()	{		$builder = new builder("lend_danbao");		$builder->title('担保人管理', '这里是担保人管理', '担保人管理', '/lend/lend_danbao')->button('add', '添加', '/lend/danbao_edit')->button('no', '禁用', '/lend/danbao_status/type/0')->button('off', '核准', '/lend/danbao_status/type/1')->button('del', '删除', '/lend/danbao_status/type/-1')->key('id', 'ID', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('addtime', '申请日期', 'time')->key('endtime', '修改时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">核准</span>', 2 => '<span style="color:#3498db;">待审核</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/lend/danbao_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',],])->lists();	}	public function danbao_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("lend_danbao", ["id" => $id], "edit");			$builder->title(($id ? '编辑担保人' : '添加担保人'), ($id ? '这里是编辑担保人' : '这里是添加担保人'), '担保人管理', '/lend/lend_danbao')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('userid', '用户ID ', 'text', '', '用户ID')->key('username', '用户名 ', 'text', '', '用户名')->key('addtime', '申请时间', 'time', '', '申请时间 (只能时间) ')->key('endtime', '修改时间', 'time', '', '修改时间 (只能时间)')->key('status', '状态', 'select', [0 => '禁用', 1 => '启用'], '')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['userid'] = iv('post.userid', 'd', '用户ID格式错误', 1);			$data['username'] = iv('post.username', 'a', '用户名格式错误', 1);			$data['addtime'] = iv('post.addtime', 'time', '申请时间格式错误', 1);			$data['endtime'] = iv('post.endtime', 'time', '修改时间格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态格式错误', 1);			$id = iv('post.id');			if ($id) {				$res = db::table('lend_danbao')->where(['id' => $id])->save($data);			} else {				$res = db::table('lend_danbao')->add($data);			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function danbao_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'lend_danbao'));	}	public function lend_set()	{		$builder = new builder("lend_set");		$builder->title('系统配置', '这里是系统配置', '系统配置', '/lend/lend_set')->button('add', '添加', '/lend/lend_set_edit')->button('no', '禁用', '/lend/lend_set_status/type/0')->button('off', '启用', '/lend/lend_set_status/type/1')->button('del', '删除', '/lend/lend_set_status/type/-1')->key('id', 'ID', 'text')->key('min', '单次最小借款', 'text')->key('max', '单次最大借款', 'text')->key('limit', '借贷人贷款比例', 'text')->key('danbao_fee', '担保收益%', 'text')->key('late_fee', '滞纳金 %', 'text')->key('backtime', '每月还款日期', 'text')->key('endtime', '修改时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/lend/lend_set_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',],])->lists();	}	public function lend_set_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("lend_set", ["id" => $id], "edit");			$builder->title(($id ? '编辑理财' : '添加理财'), ($id ? '这里是编辑理财' : '这里是添加理财'), '理财管理', '/lend/index')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('danbao_fee', '担保人收益% ', 'text', '', '%担保人获得利息的百分比')->key('late_fee', '滞纳金', 'text', '', '滞纳金%')->key('limit', '借贷限量 ', 'text', '', '%借贷限量(投资额百分比)')->key('min', '单次最小', 'text', '', '单次最小借贷量(只能整数)')->key('max', '单次最大', 'text', '', '单次最大借贷量(只能整数)')->key('backtime', '每月还款日', 'text', '', '每月还款日(整数,不可修改) ')->key('endtime', '修改时间', 'time', '', '修改时间 (只能时间)')->key('status', '状态', 'select', [0 => '禁用', 1 => '启用'], '')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$id = iv('post.id');			$data['limit'] = iv('post.limit', 'double', '借贷限量 格式错误', 1);			$data['min'] = iv('post.min', 'd', '单次最小 格式错误', 1);			$data['max'] = iv('post.max', 'd', '单次最大 格式错误', 1);			$data['danbao_fee'] = iv('post.content', 'double', '借贷限量 格式错误', 1);			$data['late_fee'] = iv('post.sort', 'double', '滞纳金格式错误', 1);			if (!$id) {				$data['backtime'] = iv('post.backtime', 'd', '还款日期时间格式错误');			}			$data['endtime'] = iv('post.endtime', 'time', '修改时间格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态格式错误', 1);			if ($id) {				$res = db::table('lend_set')->where(['id' => $id])->save($data);			} else {				$res = db::table('lend_set')->add($data);			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function lend_set_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'lend_set'));	}	public function lend_qi()	{		$builder = new builder("lend_qi");		$builder->title('借款期限配置', '这里是借款期限配置', '借款期限配置', '/lend/lend_qi')->button('add', '添加', '/lend/lend_qi_edit')->button('no', '禁用', '/lend/lend_qi_status/type/0')->button('off', '启用', '/lend/lend_qi_status/type/1')->button('del', '删除', '/lend/lend_qi_status/type/-1')->key('id', 'ID', 'text')->key('qi', '分期数', 'text')->key('name', '名称', 'text')->key('lixi', '期利率 %', 'text')->key('addtime', '添加日期', 'time')->key('endtime', '修改时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/lend/lend_qi_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',],])->lists();	}	public function lend_qi_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("lend_qi", ["id" => $id], "edit");			$builder->title(($id ? '编辑理财' : '添加理财'), ($id ? '这里是编辑理财' : '这里是添加理财'), '理财管理', '/lend/lend_qi')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('qi', ' 分期数', 'text', '', '分期数(整数)')->key('name', '名称', 'text', '', '名称')->key('lixi', '期利率', 'text', '', '期利率%')->key('addtime', '添加时间', 'time', '', '添加时间(只能时间) ')->key('endtime', '修改时间', 'time', '', '修改时间 (只能时间)')->key('status', '状态', 'select', [0 => '禁用', 1 => '启用'], '')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['qi'] = iv('post.qi', 'd', '分期数格式错误', 1);			$data['lixi'] = iv('post.lixi', 'double', '借贷限量 格式错误', 1);			$data['name'] = iv('post.name', 'a', '名称 格式错误', 1);			$data['addtime'] = iv('post.addtime', 'time', '还款日期时间格式错误', 1);			$data['endtime'] = iv('post.endtime', 'time', '修改时间格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态格式错误', 1);			$id = iv('post.id');			if ($id) {				$res = db::table('lend_qi')->where(['id' => $id])->save($data);			} else {				$res = db::table('lend_qi')->add($data);			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function lend_qi_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'lend_qi'));	}	public function lend_fee()	{		$builder = new builder("lend_fee");		$builder->title('担保人收益', '这里是担保人收益', '担保人收益', '/lend/lend_fee')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('lendid', '借款ID', 'link', [['type' => 'button', 'url' => '/lend/index/id/###', 'title' => ['from_id' => 'lendid', 'table' => 'lend', 'to_id' => 'id', 'title' => 'id'], 'field' => 'userid', 'color' => '',]])->key('qi', '期', 'text')->key('num', '本期还款', 'text')->key('fee', '担保收益', 'text')->key('addtime', '还款时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">未还</span>', 2 => '<span style="color:#3498db;">已还</span>', 3 => '<span style="color:red;">超期</span>'])->lists();	}}