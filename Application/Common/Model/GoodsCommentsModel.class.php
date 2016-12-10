<?php
namespace Common\Model;

use Think\Model;
use Think\Page;

/**
 * 健康咨询模型
 */
class GoodsCommentsModel extends Model {

	public function getAll($limit = false) {
		$pre    = C("DB_PREFIX");
		$fields = "q.*,d.title as goods_name,c.realname";
		if ($limit) {
			return $this->alias("q")->field($fields)
			            ->join("left join {$pre}document d on q.goods_id=d.id ")
			            ->join("left join {$pre}customer c on c.id=q.c_id")
			            ->limit(0, $limit)
			            ->order("vtime desc")->select();
		}
		$count = $this->count();
		$page  = new Page($count);
		$data  = $this->alias("q")->field($fields)
		              ->join("left join {$pre}document d on q.goods_id=d.id")
		              ->join("left join {$pre}customer c on c.id=q.c_id")
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