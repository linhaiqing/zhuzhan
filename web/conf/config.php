<?php
// 系统配置 系统会自动检测字段是否存在  对应sys_config表
// 添加配置 都需要在这里添加一下 后台更新缓存触发检查

return [
	// name => title
	"web_name"        => '网站名称',
	"web_title"       => '网站标题',
	"web_logo"        => '网站logo',
	"web_keywords"    => '关键字',
	"web_description" => '描述',
	"web_close"       => '网站状态',
	"web_close_cause" => '关闭原因',
	"web_icp"         => '备案信息',
	"web_cnzz"        => '统计代码',
	'mobile_code'     => '短信注册',
	'email_code'      => '邮箱注册',
	"web_reg"         => '注册协议',
	"web_footer_logo" => '网站底部logo',
	"web_waring"      => '风险警告',
	"web_invit_text"  => '专属推广语',

	"contact_gz"     => '工作日',
	"contact_jj"     => '节假日',
	"contact_qqun"   => '联系Q群',
	"contact_weibo"  => '联系微博',
	"contact_qq"     => '联系QQ',
	"contact_moble"  => '联系电话',
	"contact_weixin" => '联系微信',

	"mr_coin"           => '默认币种',
	"mr_market"         => '默认市场',
	"mr_index"          => '默认首页',
	"mr_kefu"           => '默认客服',
	"mr_index_article"  => '默认首页文章',
	"mr_footer_article" => '默认底部文章',
	"mr_market_dingjia" => '默认定价市场',

	"out_fee"      => '提现手续费',
	"out_min"      => '提现最小金额',
	"out_max"      => '提现最大金额',
	"out_bei"      => '提现金额倍数',
	"out_bank"     => '提现银行',
	"out_coin_fee" => '额外币种比例',
    "out_shuoming" => '提现说明',
	"trade_login_index" => '是否需要登录',
	"trade_login_chart" => '是否需要登录',
	"trade_login_info"  => '是否需要登录',
	"trade_login_discu" => '是否需要登录',

	"reg_close"   => '允许注册',
	"reg_waiguo"  => '允许国外手机注册',
	"reg_coin"    => '注册赠送币种',
	"reg_invit_1" => '一代赠送数量',
	"reg_invit_2" => '二代赠送数量',
	"reg_invit_3" => '三代赠送数量',
	"reg_invit"   => '开启注册赠送',

	"pay_coin"      => '充值赠送币种',
	"pay_invit_1"   => '一代赠送比例',
	"pay_invit_2"   => '二代赠送比例',
	"pay_invit_3"   => '三代赠送比例',
	"pay_invit"     => '开启充值赠送',
	"pay_invit_fee" => '充值赠送',
	"reg_invit_fee" => '赠送数量',

	"qt_moble"                     => '安全异常接收手机',
	"ranking_zzc"                  => '总资产排名显示数量',
	"trade_sh"                     => '新用户注册交易审核',
	"invit_ym"                     => '推荐默认访问页面',
	"invit_bx"                     => '推荐码必须填写',
	"template_default_index_issue" => '默认模板显示应用',

	"trade_logo" => '交易logo',
	"reg_ip"     => '注册限制ip',

	"wap_close" => 'wap网站状态',
	"wap_tz"    => '自动跳转',

	"shop_logo"   => '商城logo',
	"eyuyan_code" => '对接密匙',
	"eyuyan_ip"   => '服务器ip',

	'idrard_img_rz'                => '是否开启图片认证上传',
	"idcard_sm"  => '证件上传说明',

	"login_ip"     => '登录限制ip',
	"reg_username" => '注册限制用户名',
	"api_close"    => '头部文字',
	"top_name"     => '默认币种',

	"bbs_today_config" => '论坛',

	"web_tc_close"  => '弹窗警告状态',
	"web_tc_waring" => '弹窗风险警告',
];
