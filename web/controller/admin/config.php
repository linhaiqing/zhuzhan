<?phpnamespace admin;use Move\db;class config extends admin{	public function __construct()	{		parent::__construct();	}	public function index()	{		if (empty($_POST)) {			$data['list'] = md('sys_config')->lists();			$builder = new builder();			$builder->data($data)->url()->title('基本配置', '')->key('web_name', '网站名称', 'text', '', '网站名称')->key('web_title', '网站标题', 'text', '', '网站标题')->key('web_logo', '网站logo', 'image', '', '网站logo  200px*80px')->key('web_keywords', '网站关键字', 'textarea', '', '网站关键字')->key('web_description', '网站描叙', 'textarea', '', '网站描叙')->key('web_close', '网站状态', 'select', [0 => '禁止访问', 1 => '正常访问'], '网站状态')->key('web_close_cause', '禁止访问原因', 'textarea', '', '禁止访问原因')->key('web_icp', '备案号', 'text', '', '备案号')->key('web_cnzz', '统计代码', 'textarea', '', '统计代码')->key('web_reg', '用户注册协议', 'editor', '', '用户注册协议')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['web_name'] = iv('post.web_name');			$data['web_title'] = iv('post.web_title');			$data['web_logo'] = iv('post.web_logo');			$data['web_keywords'] = iv('post.web_keywords');			$data['web_description'] = iv('post.web_description');			$data['web_close'] = iv('post.web_close', 'd', '网站状态格式错误');			$data['web_close_cause'] = iv('post.web_close_cause');			$data['web_icp'] = iv('post.web_icp');			$data['web_cnzz'] = iv('post.web_cnzz');			$data['web_reg'] = iv('post.web_reg');			$res = 0;			foreach ($data as $k => $v) {				if (md('sys_config')->setValueOrName($k, $v)) {					$res = 1;				}			}			if ($res) {				mc("msphp_sys_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function sys()	{		if (empty($_POST)) {			$data['list'] = md('sys_config')->lists();			$builder = new builder();			$builder->data($data)->url()->title('系统配置', '')->key('web_footer_logo', '网站底部logo', 'image', '', '网站底部logo  300px*80px')->key('web_invit_text', '专属推广语', 'text', '', '网站关键字')->key('mobile_code', '短信注册/认证', 'select', [0 => '不启用', 1 => '启用'], '短信注册/认证')->key('email_code', '邮箱注册/认证', 'select', [0 => '不启用', 1 => '启用'], '邮箱注册/认证')->key('web_waring', '风险警告', 'editor', '', '风险警告')->key('web_tc_close', '弹窗警告状态', 'select', [0 => '不启用', 1 => '启用'], '首次访问弹窗警告')->key('web_tc_waring', '弹窗风险警告', 'editor', '', '弹窗风险警告')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['web_footer_logo'] = iv('post.web_footer_logo');			$data['web_invit_text'] = iv('post.web_invit_text');			$data['mobile_code'] = iv('post.mobile_code', 'd', '格式错误', 1);			$data['email_code'] = iv('post.email_code', 'd', '格式错误', 1);			$data['web_waring'] = iv('post.web_waring');			$data['web_tc_close'] = iv('post.web_tc_close', 'd', '弹窗警告状态 格式错误', 1);			$data['web_tc_waring'] = iv('post.web_tc_waring', '', '弹窗风险警告 格式错误', 1);			$res = 0;			foreach ($data as $k => $v) {				if (md('sys_config')->setValueOrName($k, $v)) {					$res = 1;				}			}			if ($res) {				mc("msphp_sys_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function contact()	{		if (empty($_POST)) {			$sys_config = db::table('sys_config')->select();			foreach ($sys_config as $k => $v) {				$data['list'] [$v['name']] = $v['value'];			}			$builder = new builder();			$builder->data($data)->url()->title('客服配置', '')->key('contact_gz', '工作日', 'text', '', '工作日时间例如 9:00-18:00')->key('contact_jj', '节假日', 'text', '', '节假日时间例如 9:30-17:30')->key('contact_qq', '联系QQ', 'text', '', '联系QQ 多个用"|"分割')->key('contact_moble', '联系电话', 'text', '', '联系电话 多个用"|"分割')->key('contact_weibo', '联系微博', 'text', '', '联系微博')->key('contact_qqun', '联系Q群', 'text', '', '联系Q群 多个用"|"分割')->key('contact_weixin', '联系微信', 'image', '', '联系微信  100px*100px')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['contact_gz'] = iv('post.contact_gz');			$data['contact_jj'] = iv('post.contact_jj');			$data['contact_qq'] = iv('post.contact_qq');			$data['contact_moble'] = iv('post.contact_moble');			$data['contact_weibo'] = iv('post.contact_weibo');			$data['contact_qqun'] = iv('post.contact_qqun');			$data['contact_weixin'] = iv('post.contact_weixin');			$res = 0;			foreach ($data as $k => $v) {				if (md('sys_config')->setValueOrName($k, $v)) {					$res = 1;				}			}			if ($res) {				mc("msphp_sys_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function qita()	{		if (empty($_POST)) {			$data['list'] = md('sys_config')->lists();			$builder = new builder();			$builder->data($data)->url()->title('其他配置', '');			if (!$data['list'] ['qt_moble']) {				$builder->key('qt_moble', '安全异常接收手机', 'text', '', '发现安全异常接收短信的手机');			} else {				$builder->key('qt_moble', '安全异常接收手机', 'readonly', '', '为了安全设置后就不能修改，如果需要修改请联系技术');			}			$builder->key('open_err_push', '是否开启手机接收异常推送', 'select', [0 => '关闭', 1 => '开启'], '需要下载动说科技安全提醒助手');			$builder->key('ranking_zzc', '总资产排名显示数量', 'text', '', '可在导航里添加   /paiming/zzc  访问这个页面')->key('trade_sh', '新注册用户交易限制', 'select', [0 => '不需要', 1 => '需要审核'], '新注册用户是否需要审核才能交易,可在用户管理里面用户进行审核')->key('invit_ym', '推荐链接默认访问页面', 'select', [0 => '首页', 1 => '注册页面'], '设置用户点击推荐链接默认访问那个页面')->key('invit_bx', '新注册是否必须填写推荐码', 'select', [0 => '可选', 1 => '必须'], '新注册是否必须填写推荐码')->key('template_default_index_issue', '默认模板首页显示认购部分', 'select', [0 => '不显示', 1 => '显示'], '默认模板首页显示认购部分')->key('top_name', '头部文字', 'text', '', '头部文字 备用')->key('domain_notice', '域名转入提示', 'editor', '', '域名转入提示')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['ranking_zzc'] = iv('post.ranking_zzc', 'd', '总资产排名显示数量 格式错误', 1);			$data['trade_sh'] = iv('post.trade_sh', 'd', '新注册用户交易限制 格式错误', 1);			$data['invit_ym'] = iv('post.invit_ym', 'd', '推荐链接默认访问页面 格式错误', 1);			$data['invit_bx'] = iv('post.invit_bx', 'd', '新注册是否必须填写推荐码 格式错误', 1);			$data['template_default_index_issue'] = iv('post.template_default_index_issue', 'd', '默认模板首页显示认购部分 格式错误', 1);			$data['top_name'] = iv('post.top_name', '', '头部文字 格式错误', 1);			$res = 0;			foreach ($data as $k => $v) {				if (md('sys_config')->setValueOrName($k, $v)) {					$res = 1;				}			}			if ($res) {				mc("msphp_sys_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function sms()	{		$builder = new builder('sys_sms');		$builder->title('短信配置', '', '短信列表', '/config/sms')->button('up', '检查更新', '/config/sms_up')->key('id', 'ID', 'text')->key('name', '标识', 'text')->key('title', '标题', 'text')->key('url', '官网', 'text')->key('sms', '文字短信', 'select', [1 => '支持', 0 => '不支持'])->key('tel', '语音短信', 'select', [1 => '支持', 0 => '不支持'])->key('guoji', '国际信', 'select', [1 => '支持', 0 => '不支持'])->key('username', '接口账号', 'username')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/config/sms_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',], ['type' => 'button2', 'url' => '/config/sms_qiyong/id/###', 'title' => '启用', 'field' => 'id', 'color' => '', 'ajax' => 'get', 'exts' => ["status" => [0]],],])->lists();	}	public function sms_up()	{		$data = [1 => ['smsbao', '短信宝', 'http://www.smsbao.com/'], 2 => ['yunsms', '云短信网', 'http://www.yunsms.cn/'], 3 => ['smschinese', '中国网建', 'http://www.smschinese.cn/'], 4 => ['ihuyi', '互亿无线', 'http://www.ihuyi.com/'], 5 => ['meilian', '美联软通', 'http://www.5c.com.cn/'],];		foreach ($data as $k => $v) {			if (!db::table('sys_sms')->where(['name' => $v[0]])->find()) {				db::table('sys_sms')->add(['name' => $v[0], 'title' => $v[1], 'url' => $v[2]]);			}		}		ajax('更新成功', 1);	}	public function sms_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("sys_sms", ["id" => $id], "edit");			$builder->title(($id ? '编辑短信' : '添加短信'), '', '短信管理', '/config/sms')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('name', '标识', 'readonly', '', '名称')->key('title', '标题', 'readonly', '', '标题')->key('url', '连接', 'readonly', '', '连接')->key('username', '接口账号', 'text', '', '')->key('password', '接口密码', 'password', '', '')->key('g_username', '接口账号', 'text', '', '国际版 没有开源不填')->key('g_password', '接口密码', 'password', '', '国际版 没有开源不填')->key('reg', '注册账号', 'text', '', '注册账号 短信模板  {code} 为验证码')->key('findpwd', '找回登录密码', 'text', '', '找回登录密码 短信模板  {code} 为验证码')->key('findpwd_pay', '找回交易密码', 'text', '', '找回交易密码 短信模板  {code} 为验证码')->key('setmoble', '设置手机号码', 'text', '', '设置手机号码 短信模板  {code} 为验证码')->key('newmoble', '更换手机号码', 'text', '', '更换手机号码 短信模板  {code} 为验证码')->key('out', '人民币提现', 'text', '', '人民币提现 短信模板  {code} 为验证码')->key('coinout', '虚拟币转出', 'text', '', '虚拟币转出 短信模板  {code} 为验证码')->key('yidi', '异地登录', 'text', '', '异地登录 短信模板  {code} 为验证码')->key('outnote', '转出提醒', 'text', '', '提现/转出提醒 短信模板 ')->key('whitelist', '编辑白名单', 'text', '', '编辑白名单 短信模板  {code} 警告内容编号')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['username'] = iv('post.username');			$data['password'] = iv('post.password');			$data['g_username'] = iv('post.g_username');			$data['g_password'] = iv('post.g_password');			$data['reg'] = iv('post.reg');			$data['findpwd'] = iv('post.findpwd');			$data['findpwd_pay'] = iv('post.findpwd_pay');			$data['setmoble'] = iv('post.setmoble');			$data['newmoble'] = iv('post.newmoble');			$data['out'] = iv('post.out');			$data['coinout'] = iv('post.coinout');			$data['whitelist'] = iv('post.whitelist');			$data['yidi'] = iv('post.yidi');			$data['outnote'] = iv('post.outnote');			$id = iv('post.id');			if ($id) {				$res = db::table('sys_sms')->where(['id' => $id])->save($data);			} else {				$res = db::table('sys_sms')->add($data);			}			if ($res) {				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function sms_status()	{		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		$builder->set_status($id, $type, 'sys_sms');	}	public function sms_qiyong()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = iv('get.id');		if (empty($id)) {			ajax('请选择要操作的数据!');		}		db::table('sys_sms')->where(['status' => 1])->save(['status' => 0]);		if (db::table('sys_sms')->where(['id' => $id])->save(['status' => 1])) {			ajax('操作成功', 1);		} else {			ajax('操作失败');		}	}	public function email()	{		if (empty($_POST)) {			$data['list'] = md('user')->email_config();			$builder = new builder();			$builder->data($data)->url()->title('邮箱配置', '')->key('host', '邮箱服务器', 'text', '', '邮箱服务器')->key('username', '邮箱', 'text', '', '你的邮箱,用于发送邮件')->key('password', '邮箱密码', 'password', '', '邮箱密码')->key('reg', '注册提示语', 'text', '', '注册提示语')->key('findpwd', '找回登录密码', 'text', '', '找回登录密码')->key('findpwd_pay', '找回交易密码', 'text', '', '找回交易密码')->key('setmoble', '设置手机号码', 'text', '', '设置手机号码')->key('findpwd_pay', '找回交易密码', 'text', '', '找回交易密码')->key('newmoble', '更换手机号码', 'text', '', '更换手机号码 短信模板  {code} 为验证码')->key('out', '人民币提现', 'text', '', '人民币提现 短信模板  {code} 为验证码')->key('coinout', '虚拟币转出', 'text', '', '虚拟币转出 短信模板  {code} 为验证码')->key('yidi', '异地登录', 'text', '', '异地登录 短信模板  {code} 为验证码')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['host'] = iv('post.host');			$data['username'] = iv('post.username');			$data['password'] = iv('post.password');			$data['reg'] = iv('post.reg');			$data['findpwd'] = iv('post.findpwd');			$data['findpwd_pay'] = iv('post.findpwd_pay');			$data['setmoble'] = iv('post.setmoble');			$data['newmoble'] = iv('post.newmoble');			$data['out'] = iv('post.out');			$data['coinout'] = iv('post.coinout');			$data['jiankong'] = iv('post.jiankong');			$data['yidi'] = iv('post.yidi');			foreach ($data as $k => $v) {				$res[] = db::table('sys_email')->where(['name' => $k])->save(['value' => $v]);			}			if (check_arr($res)) {				mc("mcemail_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function reg()	{		if (empty($_POST)) {			$sys_config = db::table('sys_config')->select();			foreach ($sys_config as $k => $v) {				$data['list'] [$v['name']] = $v['value'];			}			$mymc_coin = new \mymc\coin();			$coin_list = $mymc_coin->select();			$coin_list_select = [];			if ($coin_list) {				foreach ($coin_list as $k => $v) {					$coin_list_select[$v['name']] = $v['title'];				}			}			$builder = new builder();			$builder->data($data)->url()->title('注册配置', '')->key('reg_close', '允许注册', 'select', [0 => '禁止注册', 1 => '正常注册'], '允许注册')->key('reg_invit', '开启注册赠送', 'select', [0 => '禁止', 1 => '赠送'], '开启注册赠送  (注册完成的时候赠送)')->key('reg_coin', '注册赠送币种', 'select', $coin_list_select, '注册赠送币种')->key('reg_invit_fee', '赠送数量', 'text', '', '赠送数量  (赠送给用户自己)')->key('reg_invit_1', '一代赠送数量', 'text', '', '一代赠送数量')->key('reg_invit_2', '二代赠送数量', 'text', '', '二代赠送数量')->key('reg_invit_3', '三代赠送数量', 'text', '', '三代赠送数量')->key('reg_ip', '注册限制ip', 'textarea', '', '注册限制ip 多个用"|"分割')->key('reg_username', '注册限制用户名', 'textarea', '', '注册限制用户名 多个用"|"分割')->key('login_ip', '登录限制ip', 'textarea', '', '登录限制ip 多个用"|"分割')->key('idrard_img_rz', '是否开启图片认证上传', 'select', [0 => '关闭', 1 => '开启'], '是否开启图片认证上传,开启后实名认证部分将出现图片上传部分')->key('idcard_sm', '证件上传说明', 'editor', '', '证件上传说明')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['reg_close'] = iv('post.reg_close', 'd', '允许注册 格式错误', 1);			$data['reg_coin'] = iv('post.reg_coin', 'w', '注册赠送币种 格式错误', 1);			$data['reg_ip'] = iv('post.reg_ip', '', '注册限制ip 格式错误', 1);			$data['reg_username'] = iv('post.reg_username', '', '注册限制用户名 格式错误', 1);			$data['reg_invit'] = iv('post.reg_invit', 'd', '开启注册赠送 格式错误', 1);			$data['reg_invit_fee'] = iv('post.reg_invit_fee', 'cny', '赠送用户数量 格式错误', 1);			$data['reg_invit_1'] = iv('post.reg_invit_1', 'cny', '一代赠送数量 格式错误', 1);			$data['reg_invit_2'] = iv('post.reg_invit_2', 'cny', '二代赠送数量 格式错误', 1);			$data['reg_invit_3'] = iv('post.reg_invit_3', 'cny', '三代赠送数量 格式错误', 1);			$data['idrard_img_rz'] = iv('post.idrard_img_rz', 'd', '是否开启图片认证上传 格式错误', 1);			$data['idcard_sm'] = iv('post.idcard_sm', '', '证件上传说明 格式错误', 1);			$data['login_ip'] = iv('post.login_ip', '', '登录限制ip 格式错误', 1);			$res = 0;			foreach ($data as $k => $v) {				if (md('sys_config')->setValueOrName($k, $v)) {					$res = 1;				}			}			if ($res) {				mc("msphp_sys_config", null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function seo()	{		$builder = new builder("sys_seo");		$builder->title('页面设置', '', '', '/config/seo')->button('add', '添加', '/config/seo_edit')->button('no', '禁用', '/config/seo_status/type/0')->button('off', '启用', '/config/seo_status/type/1')->button('del', '删除', '/config/seo_status/type/-1')->search('order', ['id_desc' => 'ID降序', 'id_asc' => 'ID升序', 'sort_desc' => '索引降序', 'sort_asc' => '索引升序', 'addtime_desc' => '时间降序', 'addtime_asc' => '时间升序',], 'select')->search('status', ['0' => '全部状态', '1' => '禁用', '2' => '启用'], 'select')->search('field', ['name' => '名称', 'title' => '标题',], 'select')->search('name', '请输入查询内容', 'text')->key('id', 'ID', 'text')->key('name', '名称', 'text')->key('title', '标题', 'text')->key('show', '提示文字', 'select', [0 => '<span style="color:#DA9151;">默认隐藏</span>', 1 => '<span style="color:#3498db;">默认显示</span>'])->key('login', '需要登录', 'select', [0 => '<span style="color:#DA9151;">登录后访问</span>', 1 => '<span style="color:#3498db;">登录前可访问</span>'])->key('truename', '需要实名认证', 'select', [0 => '<span style="color:#DA9151;">无需认证</span>', 1 => '<span style="color:#3498db;">需要认证</span>'])->key('sort', '排序', 'text')->key('addtime', '添加时间', 'time')->key('endtime', '更新时间', 'time')->key('status', '状态', 'select', [0 => '<span style="color:#DA9151;">禁用</span>', 1 => '<span style="color:#3498db;">启用</span>'])->key("ext", '操作', 'link', [['type' => 'button3', 'url' => '/config/seo_edit/id/###', 'title' => '编辑', 'field' => 'id', 'color' => '',],])->lists();	}	public function seo_edit()	{		if (empty($_POST)) {			$id = input('get.id');			$builder = new builder("sys_seo", ["id" => $id], "edit");			$builder->title(($id ? '编辑页面' : '添加页面'), '', '页面设置', '/config/seo')->key('id', '编号', 'readonly', '', '数据编号')->key('id', '编号', 'hidden', '', '数据编号')->key('name', '名称', 'text', '', '名称页面的 URL   比如用户中心首页 就填写 "user/index"')->key('title', '标题', 'text', '', '标题 (全部)')->key('value', '提示文字', 'editor', '', '页面上方显示的 可用于写功能介绍')->key('show', '默认显示', 'select', [0 => '默认隐藏', 1 => '默认显示'], '提示文字显示方式')->key('login', '需要登录', 'select', [0 => '登录后访问', 1 => '登录前可访问'], '访问规则')->key('truename', '实名认证', 'select', [0 => '不需要', 1 => '需要认证'], '是否需要实名认证')->key('keywords', '关键字', 'textarea', '', '关键字 (全部)')->key('description', '描述', 'textarea', '', '描述 (全部)')->key('sort', '排序', 'text', '', '排序 (只能数字)')->key('addtime', '添加时间', 'time', '', '添加时间 (只能时间)')->key('endtime', '更新时间', 'time', '', '更新时间 (只能时间)')->key('status', '状态', 'select', [0 => '禁用', 1 => '启用'], '状态')->edit();		} else {			if (APP_DEMO) {				ajax('测试站暂时不能修改');			}			$data['name'] = iv('post.name', '', '名称格式错误', 1);			$data['title'] = iv('post.title', 'a', '标题格式错误');			$data['value'] = iv('post.value', '', '标题格式错误', 1);			$data['show'] = iv('post.show', '', '标题格式错误', 1);			$data['login'] = iv('post.login', '', '标题格式错误', 1);			$data['truename'] = iv('post.truename', 'd', '实名格式错误', 1);			$data['keywords'] = iv('post.keywords');			$data['description'] = iv('post.description');			$data['sort'] = iv('post.sort', 'a', '排序格式错误');			$data['addtime'] = iv('post.addtime', 'time', '添加时间格式错误');			$data['endtime'] = iv('post.endtime', 'time', '更新时间格式错误');			$data['status'] = iv('post.status', 'd', '状态格式错误');			$id = iv('post.id');			if ($id) {				$res = db::table('sys_seo')->where(['id' => $id])->save($data);			} else {				$res = db::table('sys_seo')->add($data);			}			if ($res) {				\Move\cache::clear();				cache(null);				ajax('操作成功', 1);			} else {				ajax('操作失败');			}		}	}	public function seo_status()	{		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$id = input('post.id');		$type = input('get.type');		$builder = new builder();		$res = $builder->set_status($id, $type, 'sys_seo');		if (isset($res[1])) {			ajax('操作成功', 1);		} else {			ajax('操作失败');		}	}}