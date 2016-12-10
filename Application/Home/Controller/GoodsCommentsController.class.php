<?php
// +----------------------------------------------------------------------
// | Todd [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;

/**
 * 评论模型控制器
 */
class GoodsCommentsController extends Controller {

	public function add() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		if (IS_POST) {
			$uid     = is_login();
			$comment = D("GoodsComments");
			$comment->create();
			$comment->c_id   = $uid; // 修改数据对象的status属性
			$comment->ctime  = NOW_TIME; // 增加time属性
			$comment->status = 0; // 增加time属性
			$id              = $comment->add();
			if ($id > 0) {
				$this->success('您的咨询已经提交,审核/回复之后即可显示.');
			}
		} else {
			$this->redirect('商品评价失败');
		}
	}

}
