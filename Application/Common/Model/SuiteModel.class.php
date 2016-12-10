<?php
namespace Common\Model;

use Think\Model;
use Think\Page;

/**
 * 健康套餐模型
 */
class SuiteModel extends Model {

//	public function getAll() {
//		$count = $this->count();
//		$page  = new Page($count);
////		$pre   = C("DB_PREFIX");
//		$data = $this->limit($page->firstRow, $page->listRows)
//		             ->order("sort,id desc")->select();
//		return array ("data" => $data, "page" => $page->show());
//	}

	public function findByTitle($title) {
		$where = array ("title" => array ("LIKE", "%" . $title . "%"));
		return $this->where($where)->find();
	}
}