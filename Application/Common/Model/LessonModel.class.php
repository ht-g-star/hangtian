<?php
namespace Common\Model;

use Think\Model;
use Think\Page;

/**
 * 健康咨询模型
 */
class LessonModel extends Model {

	public function getAll() {
		$count = $this->count();
		$page  = new Page($count);
//		$pre   = C("DB_PREFIX");
		$data = $this->limit($page->firstRow, $page->listRows)
		             ->order("sort,id desc")->select();
		return array ("data" => $data, "page" => $page->show());
	}
	

}