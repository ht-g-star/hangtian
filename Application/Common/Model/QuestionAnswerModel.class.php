<?php
namespace Common\Model;

use Think\Model;
use Think\Page;

/**
 * 健康咨询模型
 */
class QuestionAnswerModel extends Model {

	public function getAll() {
		$count = $this->count();
		$page  = new Page($count);
		$pre   = C("DB_PREFIX");
		$data  = $this->alias("q")->field("q.*,c.title as c_title")
		              ->join("left join {$pre}question_category c on q.c_id=c.id ")
		              ->limit($page->firstRow, $page->listRows)
		              ->order("vtime desc")->select();
		return array ("data" => $data, "page" => $page->show());
	}
	
	public function getByQId($id) {
		return $this->where("q_id=%d", $id)->select();
	}

}