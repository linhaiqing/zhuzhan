<?phpnamespace admin;use Move\db;use Move\ext\client;use Move\ext\page;class domain extends admin{	public function __construct()	{		parent::__construct();	}	public function index()	{		$mymc_coin = new \mymc\coin();		$coin = $mymc_coin->all_xnb();		$coin_select[0] = '全部币种';		if ($coin) {			foreach ($coin as $k => $v) {				$coin_select[$v['name']] = $v['title'];			}		}		$builder = new builder('user_domain');		$builder->title('域名管理', '这里是域名管理')->button('add', '添加', '/domain/domain_edit')->button('no', '禁用', '/domain/domain_status/type/0')->button('off', '启用', '/domain/domain_status/type/1')->button('del', '删除', '/domain/domain_status/type/-1')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '禁用', '2' => '启用'], 'select')->search('coinname', $coin_select, 'select')->search('field', ['user_id_c_user_c_id_c_username' => '用户名', 'user_id_c_user_c_id_c_moble' => '手机号', 'name' => '域名',], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('user_id', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'user_id', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'user_id', 'color' => '',]])->key('coinname', '币种', 'link', [['type' => 'button', 'url' => '/coin/index/field/name/name/###', 'title' => ['from_id' => 'coinname', 'table' => 'coin', 'to_id' => 'name', 'title' => 'title'], 'field' => 'coinname', 'color' => '',]])->key('name', '域名', 'text')->key('addtime', '添加时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/domain/domain_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',],])->lists();	}	public function domain_status()	{		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		$builder->set_status($id, $type, 'user_domain');	}	public function domain_edit()	{		if (empty($_POST)) {			$mymc_coin = new \mymc\coin();			$coin = $mymc_coin->all_xnb();			$coin_select = [];			if ($coin) {				foreach ($coin as $k => $v) {					$coin_select[$v['name']] = $v['title'];				}			}			$id = input('get.id');			$builder = new builder("user_domain", ["id" => $id], "edit");			$builder->title(($id ? '编辑域名' : '添加域名'))->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('user_id', '用户id', 'text', '', '只能数字')->key('coinname', '币种', 'select', $coin_select, '币种')->key('name', '域名', 'text', '', '添加域名')->key('addtime', '添加时间', 'time', '', '添加时间 (只能时间)')->key('sort', '排序', 'text', '', '排序（只能数字）')->key('status', '状态', 'select', [0 => '禁用', 1 => '启用'], '状态')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['coinname'] = iv('post.coinname');			$data['user_id'] = iv('post.user_id', 'd', '用户id格式错误');			$data['name'] = iv('post.name');			$data['addtime'] = iv('post.addtime', 'time', '添加时间格式错误');			$data['sort'] = iv('post.sort', 'a', '排序格式错误');			$data['status'] = iv('post.status', 'd', '状态格式错误');			$id = iv('post.id');			if ($id) {				$res = db::table('user_domain')->where(['id' => $id])->save($data);			} else {				$res = db::table('user_domain')->add($data);			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function domainin()	{		$mymc_coin = new \mymc\coin();		$list = $mymc_coin->select();		$list_select[0] = '全部币种';		if ($list) {			foreach ($list as $k => $v) {				$list_select[$v['name']] = $v['title'];			}		}		$builder = new builder('coin_in');		$builder->title('域名转入', '这里是域名转入')->button('del', '删除', '/domain/domainin_status/type/-1')->search('addtime_str', '开始时间', 'time')->search('addtime_end', '结束时间', 'time')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '等待处理', '2' => '转入成功'], 'select')->search('coinname', $list_select, 'select')->search('field', ['userid_c_user_c_id_c_username' => '用户名',], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('coinname', '币种', 'link', [['type' => 'button', 'url' => '/coin/index/field/name/name/###', 'title' => ['from_id' => 'coinname', 'table' => 'coin', 'to_id' => 'name', 'title' => 'title'], 'field' => 'coinname', 'color' => '',]])->key('type', '类型', 'text')->key('zhuanchu', '转出地址', 'text')->key('num', '转入数量', 'text')->key('mum', '实际到账', 'text')->key('queren', '确认次数', 'text')->key('addtime', '转入时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">等待处理</span>', 1 => '<span style="color:#3498db;">转入成功</span>', 2 => '<span style="color:#DA9151;">已经撤销</span>'])->key("ext", '操作', 'link', [['type' => 'button2', 'url' => '/domain/domainin_queren/id/###', 'title' => '确认到账', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [0]],], ['type' => 'button2', 'url' => '/domain/domainin_chexiao/id/###', 'title' => '撤销', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [0]],]])->lists();	}	public function domainin_status()	{		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		$builder->set_status($id, $type, 'coin_in');	}	public function domainin_queren()	{		if (APP_DEMO) {		}		$id = iv('get.id', 'd', '参数错误');		$res = md('domain')->domainin_queren($id);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function domainin_chexiao()	{		if (APP_DEMO) {		}		$id = iv('get.id', 'd', '参数错误');		$coin_in = db::table("coin_in")->where(["id" => $id])->find();		if ($coin_in["status"] != 0) {			ajax("已经处理禁止再次操作");		}		$user = db::table("user")->where(["id" => $coin_in["userid"]])->find();		if (!$user) {			ajax("用户错误");		}		$res = [];		$res[] = db::table("coin_in")->where(["id" => $coin_in["id"]])->save(["status" => 2, "endtime" => time()]);		$res[] = db::table("user_domain")->where(["name" => $coin_in["zhuanchu"]])->delete();		if ($res) {			ajax("操作成功", 1);		} else {			ajax("操作失败");		}	}	public function domainout()	{		$builder = new builder('coin_out');		$builder->title('域名转出', '这里是域名转出')->search('addtime_str', '开始时间', 'time')->search('addtime_end', '结束时间', 'time')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '等等处理', '2' => '转出成功', '3' => '已经审核', '4' => '已经撤销'], 'select')->search('field', ['userid_c_user_c_id_c_username' => '用户名',], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('coinname', '币种', 'link', [['type' => 'button', 'url' => '/coin/index/field/name/name/###', 'title' => ['from_id' => 'coinname', 'table' => 'coin', 'to_id' => 'name', 'title' => 'title'], 'field' => 'coinname', 'color' => '',]])->key('type', '类型', 'text')->key('username', '接收地址', 'text')->key('num', '转出数量', 'text')->key('addtime', '申请时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">等待处理</span>', 1 => '<span style="color:#3498db;">转出成功</span>', 2 => '<span style="color:#3498db;">已经审核</span>', 3 => '<span style="color:#3498db;">已经撤销</span>'])->key("ext", '操作', 'link', [['type' => 'button2', 'url' => '/domain/coinout_rengong/id/###', 'title' => '已经人工转账', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [2]],], ['type' => 'button2', 'url' => '/domain/coinout_queren/id/###', 'title' => '审核通过', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [0]],], ['type' => 'button2', 'url' => '/domain/coinout_chexiao/id/###', 'title' => '撤销', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [0]],], ['type' => 'button2', 'url' => '/domain/coinout_bufa/id/###', 'title' => '发送失败重新补发 谨慎操作', 'field' => 'id', 'color' => '', 'ajax' => '', 'exts' => ["status" => [1]],]])->lists();	}	public function coinout_queren()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = iv('get.id');		if (empty($id)) {			ajax('请选择要操作的数据!');		}		if (db::table('coin_out')->where(['id' => $id])->save(['status' => 2])) {			ajax('操作成功', 1);		} else {			ajax('操作失败');		}	}	public function coinout_bufa()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("coin_out", ["id" => $id], "edit");			$builder->title(($id ? '重新补发' : '添加重新补发'), ($id ? '这里是重新补发' : '这里是添加重新补发'), '虚拟币转出', '/finan/coinout')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('userid', '用户ID', 'text', '', '')->key('coinname', '币种', 'text', '', '')->key('username', '地址', 'text', '', '')->key('type', '类型', 'text', '', '')->key('status', '状态', 'select', [1 => '转出成功', 2 => '重新补发'], '如果钱包服务器发送失败 请修改为重新补发')->edit();		} else {			$data['status'] = iv('post.status', 'd', '状态格式错误');			$id = iv('post.id');			if ($id) {				$res = db::table('coin_out')->where(['id' => $id])->save($data);			} else {				ajax('数据异常');			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function coinout_rengong()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = iv('get.id');		if (empty($id)) {			ajax('请选择要操作的数据!');		}		if (db::table('coin_out')->where(['id' => $id])->save(['status' => 1])) {			ajax('操作成功', 1);		} else {			ajax('操作失败');		}	}	public function coinout_chexiao()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = iv('get.id', 'd', '参数错误');		$res = md('finan')->coinout_chexiao($id);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}}