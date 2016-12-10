<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台公共文件
 * 主要定义后台公共函数库
 */

/* 解析列表定义规则*/


function get_list_field($data, $grid) {

	// 获取当前字段数据
	foreach ($grid['field'] as $field) {
		$array = explode('|', $field);
		$temp  = $data[ $array[0] ];
		// 函数支持
		if (isset($array[1])) {
			$temp = call_user_func($array[1], $temp);
		}
		$data2[ $array[0] ] = $temp;
	}
	if (!empty($grid['format'])) {
		$value = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data2) { return $data2[ $match[1] ]; }, $grid['format']);
	} else {
		$value = implode(' ', $data2);
	}

	// 链接支持
	if (!empty($grid['href'])) {
		$links = explode(',', $grid['href']);
		foreach ($links as $link) {
			$array = explode('|', $link);
			$href  = $array[0];
			if (preg_match('/^\[([a-z_]+)\]$/', $href, $matches)) {
				$val[] = $data2[ $matches[1] ];
			} else {
				$show = isset($array[1]) ? $array[1] : $value;
				// 替换系统特殊字符串
				$href = str_replace(
					array ('[DELETE]', '[EDIT]', '[SEE]', '[LIST]'),
					array (
						'del?ids=[id]&model=[model_id]',
						'edit?id=[id]&model=[model_id]&cate_id=[category_id]',
						get_index_url() . '/index.php?s=/home/article/detail/id/[id].html',
						'index?pid=[id]&model=[model_id]&cate_id=[category_id]'
					),
					$href);

				// 替换数据变量
				$href = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data) { return $data[ $match[1] ]; }, $href);

				$val[] = '<a href="' . get_nav_url($href) . '" target="' . get_target_attr($href) . '">' . $show . '</a>';
			}
		}
		$value = implode(' ', $val);
	}
	return $value;
}

/*改造get_document_model函数
 * 获取商品模型信息
 * @param  string  $field 模型字段
 * @return array
 */
function get_modelListGoods() {
	static $list;

	/* 非法分类ID */
	if (!(is_numeric($id) || is_null($id))) {
		return '';
	}

	/* 读取缓存数据 */
	if (empty($list)) {
		$list = S('GODDS_MODEL_LIST');
	}

	/* 获取模型名称 */
	if (empty($list)) {
		$map['id']     = array ('gt', 4); //商品、非商品区别线
		$map['status'] = 1;
		$map['extend'] = 1;
		$model         = M('Model')->where($map)->field(true)->select();
		foreach ($model as $value) {
			$list[ $value['id'] ] = $value;
		}
		S('GODDS_MODEL_LIST', $list); //更新缓存
	}

	/* 根据条件返回数据 */
	if (is_null($id)) {
		return $list;
	} elseif (is_null($field)) {
		return $list[ $id ];
	} else {
		return $list[ $id ][ $field ];
	}
}

function get_adtitle($id = '') {
	$info = M('Adtitle')->where("id='$id'")->find();
	return $info['title'];
}

function get_custommenu_title($id = '') {
	$info = M('CustomMenu')->where("id='$id'")->find();
	return $info['title'];
}

/**
 * 获取非商品模型信息
 * @param  string $field 模型字段
 * @return array
 */


function get_modelListdoc() {
	static $list;

	/* 非法分类ID */
	if (!(is_numeric($id) || is_null($id))) {
		return '';
	}

	/* 读取缓存数据 */
	if (empty($list)) {
		$list = S('DOC_MODEL_LIST');
	}

	/* 获取模型名称 */
	if (empty($list)) {
		$map['id']     = array ('elt', 4);//商品、非商品区别线
		$map['status'] = 1;
		$map['extend'] = 1;
		$model         = M('Model')->where($map)->field(true)->select();
		foreach ($model as $value) {
			$list[ $value['id'] ] = $value;
		}
		S('DOC_MODEL_LIST', $list); //更新缓存
	}

	/* 根据条件返回数据 */
	if (is_null($id)) {
		return $list;
	} elseif (is_null($field)) {
		return $list[ $id ];
	} else {
		return $list[ $id ][ $field ];
	}
}

function flist() {
	$map["pid"]     = 0;
	$map["status"]  = 1;
	$map["model"]   = array ('gt', 3);
	$map["display"] = 1;

	$list = M('category')->where($map)->select();
	return $list;
}

function get_target_attr($url) {
	switch ($url) {
		case 'http://' === substr($url, 0, 7):
			$target = '_blank';
			break;
		default:
			$target = '_self';
			break;
	}
	return $target;
}


/**
 * 后台公共文件
 * 主要定义后台公共函数库
 */

/* 解析列表定义规则*/

function get_addonlist_field($data, $grid, $addon) {
	// 获取当前字段数据
	foreach ($grid['field'] as $field) {
		$array = explode('|', $field);
		$temp  = $data[ $array[0] ];
		// 函数支持
		if (isset($array[1])) {
			$temp = call_user_func($array[1], $temp);
		}
		$data2[ $array[0] ] = $temp;
	}
	if (!empty($grid['format'])) {
		$value = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data2) { return $data2[ $match[1] ]; }, $grid['format']);
	} else {
		$value = implode(' ', $data2);
	}

	// 链接支持
	if (!empty($grid['href'])) {
		$links = explode(',', $grid['href']);
		foreach ($links as $link) {
			$array = explode('|', $link);
			$href  = $array[0];
			if (preg_match('/^\[([a-z_]+)\]$/', $href, $matches)) {
				$val[] = $data2[ $matches[1] ];
			} else {
				$show = isset($array[1]) ? $array[1] : $value;
				// 替换系统特殊字符串
				$href = str_replace(
					array ('[DELETE]', '[EDIT]', '[ADDON]'),
					array ('del?ids=[id]&name=[ADDON]', 'edit?id=[id]&name=[ADDON]', $addon),
					$href);

				// 替换数据变量
				$href = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data) { return $data[ $match[1] ]; }, $href);

				$val[] = '<a href="' . U($href) . '">' . $show . '</a>';
			}
		}
		$value = implode(' ', $val);
	}
	return $value;
}

// 获取模型名称
function get_model_by_id($id) {
	return $model = M('Model')->getFieldById($id, 'title');
}

// 获取属性类型信息
function get_attribute_type($type = '') {
	// TODO 可以加入系统配置
	static $_type
	= array (
		'num'      => array ('数字', 'int(10) UNSIGNED NOT NULL'),
		'string'   => array ('字符串', 'varchar(255) NOT NULL'),
		'textarea' => array ('文本框', 'text NOT NULL'),
		'date'     => array ('日期', 'int(10) NOT NULL'),
		'datetime' => array ('时间', 'int(10) NOT NULL'),
		'bool'     => array ('布尔', 'tinyint(2) NOT NULL'),
		'select'   => array ('枚举', 'char(50) NOT NULL'),
		'radio'    => array ('单选', 'char(10) NOT NULL'),
		'checkbox' => array ('多选', 'varchar(100) NOT NULL'),
		'editor'   => array ('编辑器', 'text NOT NULL'),
		'picture'  => array ('上传图片', 'int(10) UNSIGNED NOT NULL'),
		'file'     => array ('上传附件', 'int(10) UNSIGNED NOT NULL'),
		'pictures' => array ('上传多图', 'varchar(255) NOT NULL'),
	);
	return $type ? $_type[ $type ][0] : $_type;
}

/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 *
 */
function get_status_title($status = null) {
	if (!isset($status)) {
		return false;
	}
	switch ($status) {
		case -1 :
			return '已删除';
			break;
		case 0  :
			return '禁用';
			break;
		case 1  :
			return '正常';
			break;
		case 2  :
			return '待审核';
			break;
		default :
			return false;
			break;
	}
}

// 获取数据的状态操作
function show_status_op($status) {
	switch ($status) {
		case 0  :
			return '启用';
			break;
		case 1  :
			return '禁用';
			break;
		case 2  :
			return '审核';
			break;
		default :
			return false;
			break;
	}
}

/**
 * 获取文档的类型文字
 * @param string $type
 * @return string 状态文字 ，false 未获取到
 *
 */
function get_document_type($type = null) {
	if (!isset($type)) {
		return false;
	}
	switch ($type) {
		case 1  :
			return '目录';
			break;
		case 2  :
			return '主题';
			break;
		case 3  :
			return '段落';
			break;
		default :
			return false;
			break;
	}
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type = 0) {
	$list = C('CONFIG_TYPE_LIST');
	return $list[ $type ];
}

/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group = 0) {
	$list = C('CONFIG_GROUP_LIST');
	return $group ? $list[ $group ] : '';
}

/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map 映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 *
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data, $map = array ('status' => array (1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿'))) {
	if ($data === false || $data === null) {
		return $data;
	}
	$data = (array)$data;
	foreach ($data as $key => $row) {
		foreach ($map as $col => $pair) {
			if (isset($row[ $col ]) && isset($pair[ $row[ $col ] ])) {
				$data[ $key ][ $col . '_text' ] = $pair[ $row[ $col ] ];
			}
		}
	}
	return $data;
}

/**
 * 动态扩展左侧菜单,base.html里用到
 *
 */
function extra_menu($extra_menu, &$base_menu) {
	foreach ($extra_menu as $key => $group) {
		if (isset($base_menu['child'][ $key ])) {
			$base_menu['child'][ $key ] = array_merge($base_menu['child'][ $key ], $group);
		} else {
			$base_menu['child'][ $key ] = $group;
		}
	}
}

/**
 * 获取参数的所有父级分类
 * @param int $cid 分类id
 * @return array 参数分类和父类的信息集合
 *
 */
function get_parent_category($cid) {
	if (empty($cid)) {
		return false;
	}
	$cates = M('Category')->where(array ('status' => 1))->field('id,title,pid')->order('sort')->select();
	$child = get_category($cid);    //获取参数分类的信息
	$pid   = $child['pid'];
	$temp  = array ();
	$res[] = $child;
	while (true) {
		foreach ($cates as $key => $cate) {
			if ($cate['id'] == $pid) {
				$pid = $cate['pid'];
				array_unshift($res, $cate);    //将父分类插入到数组第一个元素前
			}
		}
		if ($pid == 0) {
			break;
		}
	}
	return $res;
}

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 *
 */
function check_verify($code, $id = 1) {
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取当前分类的文档类型
 * @param int $id
 * @return array 文档类型数组
 *
 */
function get_type_bycate($id = null) {
	if (empty($id)) {
		return false;
	}
	$type_list  = C('DOCUMENT_MODEL_TYPE');
	$model_type = M('Category')->getFieldById($id, 'type');
	$model_type = explode(',', $model_type);
	foreach ($type_list as $key => $value) {
		if (!in_array($key, $model_type)) {
			unset($type_list[ $key ]);
		}
	}
	return $type_list;
}

/**
 * 获取当前文档的分类
 * @param int $id
 * @return array 文档类型数组
 *
 */
function get_cate($cate_id = null) {
	if (empty($cate_id)) {
		return false;
	}
	$title = M('Category')->where('id=' . $cate_id)->getField('title');

	return $title ? $title : '0';
}

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
	$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
	if (strpos($string, ':')) {
		$value = array ();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[ $k ] = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}

// 获取子文档数目
function get_subdocument_count($id = 0) {
	return M('Document')->where('pid=' . $id)->count();
}


// 分析枚举类型字段值 格式 a:名称1,b:名称2
// 暂时和 parse_config_attr功能相同
// 但请不要互相使用，后期会调整
function parse_field_attr($string) {
	if (0 === strpos($string, ':')) {
		// 采用函数定义
		return eval('return ' . substr($string, 1) . ';');
	} elseif (0 === strpos($string, '[')) {
		// 支持读取配置参数（必须是数组类型）
		return C(substr($string, 1, -1));
	}

	$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
	if (strpos($string, ':')) {
		$value = array ();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[ $k ] = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}

/**
 * 获取行为数据
 * @param string $id 行为id
 * @param string $field 需要获取的字段
 *
 */
function get_action($id = null, $field = null) {
	if (empty($id) && !is_numeric($id)) {
		return false;
	}
	$list = S('action_list');
	if (empty($list[ $id ])) {
		$map         = array ('status' => array ('gt', -1), 'id' => $id);
		$list[ $id ] = M('Action')->where($map)->field(true)->find();
	}
	return empty($field) ? $list[ $id ] : $list[ $id ][ $field ];
}

/**
 * 根据条件字段获取数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 *
 */
function get_document_field($value = null, $condition = 'id', $field = null) {
	if (empty($value)) {
		return false;
	}

	//拼接参数
	$map[ $condition ] = $value;
	$info              = M('Model')->where($map);
	if (empty($field)) {
		$info = $info->field(true)->find();
	} else {
		$info = $info->getField($field);
	}
	return $info;
}

/**
 * 获取行为类型
 * @param intger $type 类型
 * @param bool $all 是否返回全部类型
 *
 */
function get_action_type($type, $all = false) {
	$list = array (
		1 => '系统',
		2 => '用户',
	);
	if ($all) {
		return $list;
	}
	return $list[ $type ];
}

function get_status_title_bymodel($status = null, $model) {
	if (!isset($status)) {
		return false;
	}
	if ($model == 'exchange') {
		switch ($status) {
			case 1  :
				return '已提交';
				break;
			case 2  :
				return '已同意';
				break;
			case 3  :
				return '已拒绝';
				break;
			case 4  :
				return '换货中';
				break;
			case 5  :
				return '已完成';
				break;
			default :
				return false;
				break;
		}
	}
	if ($model == 'backlist') {
		switch ($status) {
			case 1  :
				return '已提交';
				break;
			case 2  :
				return '已同意';
				break;
			case 3  :
				return '已拒绝';
				break;
			case 4  :
				return '退货中';
				break;
			case 5  :
				return '已完成';
				break;
			default :
				return false;
				break;
		}
	}
	if ($model == 'cancel') {
		switch ($status) {
			case 1  :
				return '已提交';
				break;
			case 2  :
				return '已同意';
				break;
			case 3  :
				return '已拒绝';
				break;
			default :
				return false;
				break;
		}
	}
	if ($model == 'order') {
		switch ($status) {
			case 0  :
				return '所有';
				break;
			case 1  :
				return '已提交';
				break;
			case 2  :
				return '已发货';
				break;
			case 3  :
				return '已签收';
				break;
			default :
				return false;
				break;
		}
	}
	if ($model == 'express') {
		switch ($status) {

			case 1  :
				return '未使用';
				break;
			case 2  :
				return '已使用';
				break;

			default :
				return false;
				break;
		}
	}
	if ($model == 'records') {
		switch ($status) {

			case 1  :
				return '首页';
				break;
			case 2  :
				return '列表页';
				break;
			case 3  :
				return '内容页';
				break;

			default :
				return false;
				break;
		}
	}

}

function get_Ordernum($status, $model) {
	if ($status) {
		$map['status'] = $status;
	}
	$map['id'] = array ('gt', 0);
	$num       = M($model)->where($map)->count();
	return $num ? $num : 0;
}

function exportExcel($expTitle, $expCellName, $expTableData) {
	$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
	$fileName = $expTitle . date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
	$cellNum  = count($expCellName);
	$dataNum  = count($expTableData);
	vendor("PHPExcel");
	$objPHPExcel = new \PHPExcel();
	$cellName    = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

	$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[ $cellNum - 1 ] . '1');//合并单元格
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . '  Export time:' . date('Y-m-d H:i:s'));
	for ($i = 0; $i < $cellNum; $i++) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[ $i ] . '2', $expCellName[ $i ][1]);
	}
	// Miscellaneous glyphs, UTF-8
	for ($i = 0; $i < $dataNum; $i++) {
		for ($j = 0; $j < $cellNum; $j++) {
			$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[ $j ] . ($i + 3), " " . $expTableData[ $i ][ $expCellName[ $j ][0] ]);
		}
	}

	header('pragma:public');
	header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
	header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}

//导入数据方法
function excel_import($filename, $exts = 'xls') {
	//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
	vendor("PHPExcel");
	//创建PHPExcel对象，注意，不能少了\
	$PHPExcel = new \PHPExcel();
	//如果excel文件后缀名为.xls，导入这个类
	if ($exts == 'xlsx') {
		vendor("PHPExcel.Reader.Excel2007");
		$PHPReader = new \PHPExcel_Reader_Excel2007();
	} else {
		vendor("PHPExcel.Reader.Excel5");
		$PHPReader = new \PHPExcel_Reader_Excel5();
	}

	try {
		//载入文件
		$PHPExcel = $PHPReader->load($filename);
	} catch (Exception $e) {
		dump($e);
		exit("文件格式有误，请重新保存");
	}

	//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
	$currentSheet = $PHPExcel->getSheet(0);
	//获取总列数
	$allColumn = $currentSheet->getHighestColumn();
	//获取总行数
	$allRow = $currentSheet->getHighestRow();
	//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
	for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
		//从哪列开始，A表示第一列
		for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
			//数据坐标
			$address = $currentColumn . $currentRow;
			//读取到的数据，保存到数组$arr中
			$data[ $currentRow ][ $currentColumn ] = $currentSheet->getCell($address)->getValue();
		}
	}
	return $data;
//		$this->save_import($data);
}

//保存导入数据
function save_import($data) {
	$Model = M('volunteer');
	foreach ($data as $k => $v) {
		if ($k >= 2) {
			$realname = trim($v['A']);
			$sex      = trim($v['B']);
			$sex      = ($sex == '男' ? 1 : 0);
			$id_num   = trim($v['C']);
			$tel      = trim($v['D']);
			$num      = trim($v['E']);
			$group    = trim($v['F']);
			$group2   = trim($v['G']);
			if (empty($realname) && empty($id_num) && empty($tel) && empty($num)) {
				continue;
			}
			if (empty($realname) || empty($id_num) || empty($tel)) {
				$this->error("请检查Excel中第{$k}行的数据.导入失败!");
				return;
			}
			if (strlen($id_num) != 15 && strlen($id_num) != 18) {
				$this->error("请检查Excel中第{$k}行的身份证信息,输入错误。导入失败");
				return;
			}
			if ($Model->where("id_num='" . $id_num . "'")->find()) {
				$this->error("Excel中第{$k}行的身份证已经存在！导入失败!");
				return;
			}
			$password = md5(substr($id_num, -6));
			if (!$num) {
				$count = M("volunteer")->field("id")->count();
				if ($count > 1000) {
					$num = $this->generate_num($count);
				} else {
					$this->error("前1000位义工需要有工号");
					return false;
				}
			}
			$result = $Model->add(array (
				                      "realname" => $realname,
				                      "password" => $password,
				                      "sex"      => $sex,
				                      "id_num"   => $id_num,
				                      "tel"      => $tel,
				                      "num"      => $num,
				                      "group"    => $group,
				                      "group2"   => $group2
			                      ));
		}
	}

	if ($result) {
		$this->success('导入成功!', U('lists'));
	} else {
		$this->error('Excel格式错误,请下载模板进行导入操作!');
	}
	//print_r($info);

}

function get_am_icon_by_title($title) {
	switch ($title) {
		case '首页':
			$icon = 'home';
			break;
		case '会员':
			$icon = 'user';
			break;
		case '商品':
			$icon = 'gift';
			break;
		case '文章':
			$icon = 'file';
			break;
		case '订单':
			$icon = 'th-list';
			break;
		case '数据':
			$icon = 'list-ul';
			break;
		case '用户':
			$icon = 'user';
			break;
		case '广告':
			$icon = 'life-ring';
			break;
		case '扩展':
			$icon = 'magnet';
			break;
		case '微信':
			$icon = 'weixin';
			break;
		case '体检':
			$icon = 'stethoscope';
			break;
		case '内容':
			$icon = 'cloud';
			break;
		case '会员列表':
			$icon = 'bars';
			break;
		case '会员回收站':
			$icon = 'recycle';
			break;
		case '会员消费导入':
			$icon = 'plus-square';
			break;
		case '会员管理':
			$icon = 'user';
			break;
		case '会员系统设置':
			$icon = 'cogs';
			break;
		case '读卡器设置':
			$icon = 'gear';
			break;
		case '企业管理':
			$icon = 'building';
			break;
		case '企业帐号':
			$icon = 'users';
			break;
		case '网站设置':
			$icon = 'globe';
			break;
		case '商品分类':
			$icon = 'tags';
			break;
		case '文章分类':
			$icon = 'file-text';
			break;
		case '模型管理':
			$icon = 'sun-o';
			break;
		case '配置管理':
			$icon = 'tasks';
			break;
		case '菜单管理':
			$icon = 'folder';
			break;
		case '导航管理':
			$icon = 'puzzle-piece';
			break;
		case '数据备份':
			$icon = 'database';
			break;
		case '备份数据库':
			$icon = 'database';
			break;
		case '还原数据库':
			$icon = 'reply';
			break;
		case '预约管理':
			$icon = 'file-o';
			break;
		case '预约列表':
			$icon = 'file-text-o';
			break;
		case '用户管理':
			$icon = 'users';
			break;
		case '用户信息':
			$icon = 'male';
			break;
		case '权限管理':
			$icon = 'sitemap';
			break;
		case '微信配置':
			$icon = 'weixin';
			break;
		case '公众号配置':
			$icon = 'cog';
			break;
		case '自定义菜单管理':
			$icon = 'th';
			break;
		case '自动回复':
			$icon = 'reply';
			break;
		case '套餐管理':
			$icon = 'bullseye';
			break;
		case '预约时间设置':
			$icon = 'clock-o';
			break;
		case '评价管理':
			$icon = 'comments';
			break;
		case '预约订单':
			$icon = 'th-large';
			break;
		case '预约信息':
			$icon = 'medkit';
			break;
		case '咨询管理':
			$icon = 'cog';
			break;
		case '咨询列表':
			$icon = 'code-fork';
			break;
		case '咨询分类':
			$icon = 'asterisk';
			break;
		case '讲坛管理':
			$icon = 'paper-plane';
			break;
		case '名医管理':
			$icon = 'user-md';
			break;
		default:
			$icon = 'bars';
			break;

	}
	return "am-icon-" . $icon;
}

/**
 * 增加日志类型记录
 * @param $model_name
 * @param $data
 * @return mixed
 */
function addLog($model_name, $data) {
	return M($model_name)->add($data);
}


function get_img_str($url) {
	if ($url) {
		return "<img src='{$url}' height='100px'  />";
	} else {
		return "";
	}
}


function excelTime($date, $time = false) {
	if (function_exists('GregorianToJD')) {
		if (is_numeric($date)) {
			$jd        = GregorianToJD(1, 1, 1970);
			$gregorian = JDToGregorian($jd + intval($date) - 25569);
			$date      = explode('/', $gregorian);
			$date_str  = str_pad($date [2], 4, '0', STR_PAD_LEFT)
			             . "-" . str_pad($date [0], 2, '0', STR_PAD_LEFT)
			             . "-" . str_pad($date [1], 2, '0', STR_PAD_LEFT)
			             . ($time ? " 00:00:00" : '');
			return $date_str;
		}
	} else {
		$date = $date > 25568 ? $date + 1 : 25569;
		/*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
		$ofs  = (70 * 365 + 17 + 2) * 86400;
		$date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
	}
	return $date;
}

function get_customer_name($id) {
	if ($id) {
		return M("customer")->where("id=%d", $id)->cache(true)->getField("realname");
	}
	
}
function get_card_num_by_id($id) {
	if ($id) {
		return M("customer")->where("id=%d", $id)->cache(true)->getField("card_num");
	}
}
function get_carge_reason($code) {
	return \Admin\Model\ChargeReasonModel::getReason($code);
}


function setAirport($airportcode = ""){
    if(!empty($airportcode)){
        $airportArr = S("airportArr");

        if(empty($airportArr)){
            $airportList = M("jipiao_airport")->select();
            $airportArr = array();
            foreach ($airportList as $key => $value) {
                $airportArr[$value['airportcode']] = $value['airportname'];
            }
            S("airportArr", $airportArr, 86400);
        }

        $airportArr = S("airportArr");

        return $airportArr[$airportcode];
    }else{
        return "";
    }
}