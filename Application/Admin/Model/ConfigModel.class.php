<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;

use Think\Model;

/**
 * 配置模型
 *
 */
class ConfigModel extends Model {
	protected $_validate
		= array (
			array ('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
			array ('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
			array ('title', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
		);

	protected $_auto
		= array (
			array ('name', 'strtoupper', self::MODEL_BOTH, 'function'),
			array ('create_time', NOW_TIME, self::MODEL_INSERT),
			array ('update_time', NOW_TIME, self::MODEL_BOTH),
			array ('status', '1', self::MODEL_BOTH),
		);

	/**
	 * 获取配置列表
	 * @return array 配置数组
	 *
	 */
	public function lists() {
		$map  = array ('status' => 1);
		$data = $this->where($map)->field('type,name,value')->select();

		$config = array ();
		if ($data && is_array($data)) {
			foreach ($data as $value) {
				$config[ $value['name'] ] = $this->parse($value['type'], $value['value']);
			}
		}
		return $config;
	}

	/**
	 * 根据配置类型解析配置
	 * @param  integer $type 配置类型
	 * @param  string $value 配置值
	 *
	 */
	private function parse($type, $value) {
		switch ($type) {
			case 3: //解析数组
				$array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
				if (strpos($value, ':')) {
					$value = array ();
					foreach ($array as $val) {
						list($k, $v) = explode(':', $val);
						$value[ $k ] = $v;
					}
				} else {
					$value = $array;
				}
				break;
		}
		return $value;
	}

	/**
	 * 获取一个参数
	 * @param $name
	 * @return array
	 */
	public function getOne($name) {
		$map  = array ('status' => 1, 'name' => $name);
		$data = $this->where($map)->field('type,name,value')->find();
		if (!$data) {
			return false;
		}
		$config = array ();
		if ($data && is_array($data)) {
			$config[ $data['name'] ] = $this->parse($data['type'], $data['value']);
		}
		return $config;
	}


	/**
	 * 设置一个参数
	 * @param $name
	 * @param $value
	 * @param bool|false $create
	 * @param string $title
	 * @return bool|mixed
	 */
	public function setOne($name, $value, $create = false, $title = '') {
		$map  = array ('status' => 1, 'name' => $name);
		$data = $this->where($map)->field('type,name,value')->find();
		S('DB_CONFIG_DATA', null);
		if ($data) {

			return $this->where($map)->setField("value", $value);
		} else {
			if ($create) {
				return $this->add(array (
					                  "name"        => $name,
					                  "value"       => $value,
					                  "type"        => 0,
					                  'title'       => $title,
					                  'create_time' => time(),
					                  'update_time' => time(),
					                  'status'      => 1
				                  ));
			} else {
				$this->error = "没有该项目";
				return false;
			}
		}
	}
}
