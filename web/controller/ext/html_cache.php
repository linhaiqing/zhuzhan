<?php
htmlMemcache::init();
htmlMemcache::run();

class htmlMemcache
{
	public static $mem_object;
	public static $options;
	public static $handler;

	public static function init($config = [])
	{
		self::$options = array_merge(["host" => "127.0.0.1", "port" => "11211", "prefix" => "move_", "expire" => 3600], $config);
		if (class_exists("Memcache")) {
			self::$handler = new \Memcache();
			if (!self::$handler->connect(self::$options["host"], self::$options["port"])) {
				exit("memcache 无法连接![可能是服务未启动...]");
			}
		} elseif (class_exists("Memcached")) {
			$servers = [];
			self::$handler = new \Memcached();
			$servers = [[self::$options["host"], self::$options["port"]]];
			self::$handler->addServers($servers);
			if (!self::$handler->set(md5("memcached"), "OK")) {
				exit("memcached 无法连接![可能是服务未启动...]");
			}
		} else {
			exit("memcache or memcached 扩展不存在!");
		}
	}

	public static function run($data = null)
	{
		$key = md5("Html_" . @md5($_SERVER["REQUEST_URI"]));
		if ($data === null) {
			$display_cache_list = self::get("display_cache_list");
			$display_cache_list = json_decode($display_cache_list, true);
			if ($display_cache_list && isset($display_cache_list[$key])) {
				$data = self::get($key);
				if ($data) {
					echo $data;
					exit();
				}
			}
		} else {
			$display_cache_list = self::get("display_cache_list");
			if (!$display_cache_list) {
				$display_cache_list = [];
			} else {
				$display_cache_list = json_decode($display_cache_list, true);
			}
			$display_cache_list[$key] = 1;
			self::set("display_cache_list", json_encode($display_cache_list));
			self::set($key, $data);
			echo self::get($key);
			exit;
		}
	}

	public static function get($key)
	{
		return self::$handler->get($key);
	}

	public static function set($key, $value, $expire = null)
	{
		if (is_null($expire)) {
			$expire = self::$options["expire"];
		}
		if (self::$handler->set($key, $value, 0, $expire)) {
			return true;
		}
		return false;
	}

	public static function rm($key)
	{
		return self::$handler->delete($key);
	}

	public static function clear()
	{
		return self::$handler->flush();
	}
} 