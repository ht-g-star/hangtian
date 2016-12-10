<?php
namespace Common\Model;

use Think\Model;
use Think\Page;

/**
 * 健康咨询模型
 */
class QuestionModel extends Model {

	public function getAll($limit = false) {
		$pre = C("DB_PREFIX");

		if ($limit) {
			return $this->alias("q")->field("q.*,c.title as c_title")
			            ->join("left join {$pre}question_category c on q.c_id=c.id ")
			            ->limit(0, $limit)
			            ->order("vtime desc")->select();
		}
		$count = $this->count();
		$page  = new Page($count);
		$data  = $this->alias("q")->field("q.*,c.title as c_title")
		              ->join("left join {$pre}question_category c on q.c_id=c.id ")
		              ->limit($page->firstRow, $page->listRows)
		              ->order("vtime desc")->select();
		return array ("data" => $data, "page" => $page->show());
	}

	public function getByCId($cid) {
		$count = $this->where("c_id=%d", $cid)->count();
		$page  = new Page($count);
		$pre   = C("DB_PREFIX");
		$data  = $this->alias("q")->field("q.*,c.title as c_title")
		              ->where("q.c_id=%d", $cid)
		              ->join("left join {$pre}question_category c on q.c_id=c.id ")
		              ->limit($page->firstRow, $page->listRows)
		              ->order("vtime desc")->select();
		return array ("data" => $data, "page" => $page->show());
	}
}