<?phpnamespace admin;use Move\db;class help extends admin{	public function __construct()	{		parent::__construct();		if (!$this->is_game('help')) {			ajax('这个应用你没有购买');		}	}	public function index()	{		$builder = new builder("help");		$builder->title('问题中心', '这里是问题中心', '问题列表', '/help/index')->button('del', '删除', '/help/status/type/-1')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '禁用', '2' => '已回复', '3' => '未回复'], 'select')->search('name', '请输入查询内容', 'text')->key('id', '编号', 'text')->key('userid', '用户名', 'link', [['type' => 'button', 'url' => '/user/index/field/id/name/###', 'title' => ['from_id' => 'userid', 'table' => 'user', 'to_id' => 'id', 'title' => 'username'], 'field' => 'userid', 'color' => '',]])->key('type', '类型', 'link', [['type' => 'button', 'url' => '/help/type/field/id/name/###', 'title' => ['from_id' => 'type', 'table' => 'help_type', 'to_id' => 'id', 'title' => 'type'], 'field' => 'type', 'color' => '',]])->key('contact', '联系方式', 'text')->key('status', '状态', 'select', [0 => "禁用", 1 => '<span style="color:#3498db;">已回复</span>', 2 => '<span style="color:red;">未回复</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/help/edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',]])->lists();	}	public function edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("help", ["id" => $id], "edit");			$builder->title(($id ? '编辑问题' : '添加问题'), ($id ? '这里是编辑问题' : '这里是添加问题'), '问题中心', '/help/index')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('userid', '用户ID', 'text', '', '提问用户')->key('type', '问题类型', 'select', 'help_type_c_id_c_type', '问题类型')->key('content', '问题内容', 'text', '', '问题内容')->key('contact', '联系方式', 'text', '', '联系方式')->key('back', '回复内容', 'text', '', '回复内容(200字以内)')->key('addtime', '提问时间', 'time', '', '提问时间')->key('endtime', '回答时间', 'time', '', '回答时间')->key('status', '状态', 'select', [1 => "已回复", 2 => "未回复"], '状态')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['userid'] = iv('post.userid', 'd', '用户ID 格式错误', 1);			$data['type'] = iv('post.type', 'd', '问题类型 格式错误', 1);			$data['content'] = iv('post.content', 'a', '问题内容 格式错误', 1);			$data['contact'] = iv('post.contact', 'a', '联系方式 格式错误', 1);			$data['back'] = iv('post.back', 'a', '回复内容 格式错误', 1);			$data['addtime'] = iv('post.addtime', 'time', '提问时间 格式错误', 1);			$data['endtime'] = iv('post.endtime', 'time', '回答时间 格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态 格式错误', 1);			$id = iv('post.id');			if (mb_strlen($data['content']) > 200) {				ajax("问题过长");			}			if (mb_strlen($data['back']) > 200) {				ajax("回复过长");			}			if ($id) {				$res = db::table("help")->where(["id" => $id])->save($data);			} else {				$res = db::table("help")->add($data);			}			if ($res) {				ajax('操作成功', 1, "/admin/help/index");			} else {				ajax('操作失败');			}		}	}	public function status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'help'));	}	public function type()	{		$builder = new builder("help_type");		$builder->title('类型管理', '这里是类型管理', '类型管理', '/help/type')->button('add', '添加', '/help/type_edit')->button('no', '禁用', '/help/type_status/type/0')->button('off', '启用', '/help/type_status/type/1')->button('del', '删除', '/help/type_status/type/-1')->search('name', '请输入查询内容', 'text')->key('id', '编号', 'text')->key('type', '问题类型', 'text')->key('addtime', '添加时间', 'time')->key('endtime', '修改时间', 'time')->key('status', '状态', 'select', [0 => "禁用", 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/help/type_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',]])->lists();	}	public function type_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("help_type", ["id" => $id], "edit");			$builder->title(($id ? '编辑问题类型' : '添加问题类型'), ($id ? '这里是编辑问题类型' : '这里是添加问题类型'), '问题类型管理', '/help/type')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('type', '问题类型', 'text', '', '问题类型')->key('addtime', '添加时间', 'time', '', '添加时间')->key('endtime', '修改时间', 'time', '', '修改时间')->key('status', '状态', 'select', [0 => "禁用", 1 => "启用"], '记录状态')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['type'] = iv('post.type', 'a', '问题类型 格式错误', 1);			$data['addtime'] = iv('post.addtime', 'time', '添加时间 格式错误', 1);			$data['endtime'] = iv('post.endtime', 'time', '修改时间 格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态 格式错误', 1);			$id = iv('post.id');			if ($id) {				$res = db::table("help_type")->where(["id" => $id])->save($data);			} else {				$res = db::table("help_type")->add($data);			}			if ($res) {				ajax('操作成功', 1, "/admin/help/type");			} else {				ajax('操作失败');			}		}	}	public function type_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'help_type'));	}	public function news()	{		$builder = new builder("help_newslog");		$builder->title('消息管理', '这里是消息管理', '消息管理', '/help/news')->button('add', '添加', '/help/news_edit')->button('off', '发送', '/help/news_send/type/1')->button('del', '撤回', '/help/news_send/type/0')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '未发送', '2' => '已发送'], 'select')->search('name', '请输入查询内容', 'text')->key('id', '编号', 'text')->key('title', '消息标题', 'text')->key('addtime', '添加时间', 'time')->key('endtime', '修改时间', 'time')->key('status', '状态', 'select', [0 => "未发送", 1 => '<span style="color:#3498db;">已发送</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/help/news_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',]])->lists();	}	public function news_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$newslog = db::table("help_newslog")->where(["id" => $id])->find();			$builder = new builder("help_newslog", ["id" => $id], "edit");			$builder->title(($id ? '编辑消息' : '添加消息'), ($id ? '这里是编辑消息' : '这里是添加消息'), '消息管理', '/help/news')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('title', '标题', 'text', '', '标题')->key('content', '内容', 'text', '', '内容(200字以内)')->key('addtime', '添加时间', 'time', '', '添加时间')->key('endtime', '修改时间', 'time', '', '修改时间')->key('status', '状态', 'select', [0 => "未发送", 1 => "已发送"], '记录状态')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['title'] = iv('post.title', 'a', '消息标题 格式错误', 1);			$data['content'] = iv('post.content', 'a', '消息内容 格式错误', 1);			$data['addtime'] = iv('post.addtime', 'time', '添加时间 格式错误', 1);			$data['endtime'] = iv('post.endtime', 'time', '修改时间 格式错误', 1);			$data['status'] = iv('post.status', 'd', '状态 格式错误', 1);			$id = iv('post.id');			if (mb_strlen($data['content']) > 200) {				ajax("内容过长");			}			if ($id) {				$res = db::table("help_newslog")->where(["id" => $id])->save($data);			} else {				$res = db::table("help_newslog")->add($data);			}			if ($res) {				ajax('操作成功', 1, "/admin/help/news");			} else {				ajax('操作失败');			}		}	}	public function news_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		show($builder->set_status($id, $type, 'help_newslog'));	}	public function news_send()	{		$id = iv("post.id");		$type = iv("get.type");		if (check_arr($id)) {			$res = md('help')->send($id, $type);			if (isset($res[1]) && $res[1]) {				ajax($res[0], 1);			} else {				ajax($res[0]);			}		} else {			ajax("请选择要操作的消息");		}	}}