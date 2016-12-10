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
class CommentController extends Controller {

	public function index() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$listid   = I('get.id', 0, 'intval');
		$shoplist = M('shoplist');
		$list     = $shoplist->find($listid);
		//没有评论过
		if ($list["iscomment"] == 1) {
			$this->assign('comment', $list);
			$title            = get_good_name($list["goodid"]);
			$this->meta_title = '评价商品_' . $title;
			$this->display();
		} else {
			$this->error('商品评价过');
		}
	}

	public function add() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		if (IS_POST) {
			$uid     = is_login();
			$comment = D("comment");
			$comment->create();
			$comment->uid         = $uid; // 修改数据对象的status属性
			$comment->create_time = NOW_TIME; // 增加time属性
			$comment->status      = 1; // 增加time属性
			$id                   = $comment->add();
			if ($id > 0) {
				$shopid = I("post.shopid");
				M('shoplist')->where("id='$shopid'")->setField("iscomment", "2");
				$this->success('商品评价成功', U('Center/index'));
			}
		} else {
			$this->redirect('商品评价失败');
		}
	}

	public function lists() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid        = is_login();
		$map['uid'] = $uid;
		$list       = D("Comment")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("Comment")->getPage($map);
		$this->assign('page', $page);// 
		$user             = session("user_auth");
		$this->meta_title = $user['realname'] . '的评论管理';
		/* 差评*/
		$bad    = M("comment")->where("status='1' and uid='$uid' and score='1'")->count();
		$common = M("comment")->where("status='1' and uid='$uid' and score='2'")->count();
		$best   = M("comment")->where("status='1' and uid='$uid' and score='3'")->count();
		$this->assign('bad', $bad);
		$this->assign('common', $common);
		$this->assign('best', $best);
		$this->display();
	}
}
