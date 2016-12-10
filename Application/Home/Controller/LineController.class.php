<?php
/**
 * 度假套餐
 */
namespace Home\Controller;

use Think\Log;

class LineController extends AuthController {

	public function index(){
		$this->redirect("Line/getLineList");
	}

	/**
	 * 度假线路
	 */
	public function getLineList() {
		 //取消强制登陆
		// if (!is_login()) {
  //           cookie('__forward__', U('getLineList', array('info'=>$data)));
  //           $this->redirect("User/login");
  //           exit;
  //       }
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetLinelist";
		$data=$_GET;
		$data['type'];
		if($data['type']){
			$url=$url.'&type='.$data['type'];
		}else{
			$url=$url.'&type=1';
		}
		if($data['xl_type']){
			$url=$url.'&xl_type='.$data['xl_type'];
		}else{
			$url=$url.'&xl_type=2';
		}
		if($data['city_id']){
			$url=$url.'&city_id='.$data['city_id'];
			$this->assign('cityid',$data['city_id']);
		}else{
			//$url=$url.'&city_id=';
		}
		if($data['section_id']){
			$url=$url.'&section_id='.$data['section_id'];
			$this->assign('sectionid',$data['section_id']);
		}else{
			//$url=$url.'&section_id=';
		}
		if($data['cfyf']){
			$url=$url.'&cfyf='.$data['cfyf'];
			$this->assign('datetime',$data['cfyf']);
		}
		if($data['keyword']){
			$url=$url.'&keywords='.urlencode($data['keyword']);
			$this->assign('keyword',$data['keyword']);
		}
		$linelist=$this->getJsonByUrl($url.'&show_count=0');
	

		$count=$this->getJsonByUrl($url.'&show_count=1');
		$url2="http://www.myvacation.cn/wuxi/api.php?Action=GetLineSections&type=1&xl_type=2";
		$cache = $this->getJsonByUrl($url2);
		if(!$cache['result']){
			//无结果
			//返回错误信息
		}else{
//			foreach($cache as $k=>$v){
//				$url1="http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1&xl_id=".$v['xl_id'];
//				$cache['xl_info']=$this->getJsonByUrl($url1);
//			}
			unset($linelist['result']);
			unset($linelist['msg']);
			unset($cache['result']);
			unset($cache['msg']);
			$linecity = 0;
			foreach($cache as $k=>$v){
				$linecity+=$v['section_num'];
				foreach($v['city'] as $kk=>$vv){
					$urlaa = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDateShifts&type=1&xl_type=2&city_id=".$vv['city_id']."&section_id=".$v['section_id']."&keywords=";
					$cfdate = $this->getJsonByUrl($urlaa);
				}
			}
			unset($cfdate['result']);
			unset($cfdate['msg']);
			$this->assign('linecity',$linecity);
			$linelist=$this->getPage($linelist,$count,$_GET['p'],20);
			$this->assign('linelist',$linelist);
			$this->assign('cache',$cache);
			$cfsj = array();
			foreach($cfdate as $k=>$v){
				if('出团日期不限'!=$v['cfrq']){
					$cfsj[$k]['cfrqstr']=substr($v['cfrq'],0,4).'年'.substr($v['cfrq'],4,2).'月';
					$cfsj[$k]['cfrq'] = $v['cfrq'];
				}
			}
			$this->assign('cfsj',$cfsj);
		}
		$this->meta_title = '全国疗养线路';
		$this->display();
		
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetLinelist&type=1&xl_type=2";
		$linelist=$this->getJsonByUrl($url.'&show_count=0');
	}
	
	public function lineDetail(){
		$url = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1";
		$data=$_GET;
		if($data['xl_id']){
			$url=$url.'&xl_id='.$data['xl_id'];
			$this->assign('lineid',$data['xl_id']);
		}
		$line=$this->getJsonByUrl($url);

		// print_r($line);

		if(!$line['result']){
			//无结果
			//返回错误信息
		}else{
			unset($line['result']);
			unset($line['msg']);
			if($line['xl_biaoqian']){
				$biaoqin =array_filter(explode(',', $line['xl_biaoqian']));
				$this->assign('biaoqin',$biaoqin);
			}
			$this->assign('line',$line);
			
			$url1 = "http://www.myvacation.cn/m/members/action/get_xl_jiage_string_wuxi.php?xl_id=".$data['xl_id'];
			$lineinfo=$this->getJsonByUrl1($url1);
			$lineinfo =array_filter(explode(';', $lineinfo));
			foreach($lineinfo as $k=>$v){
				$lineinfo[$k] = array_filter(explode('|', $v));
			}

			// print_r($lineinfo);

			$this->assign('lineinfocount',count($lineinfo));
			$this->assign('lineinfo',$lineinfo);
		}
		$this->meta_title = '全国疗养线路';
		$this->display();
	}

	public function order(){
		if(IS_POST){
			//todo 验证
			$data=I('post.');
			$data['uid']=$_SESSION['onethink_home']['user_auth']['id'];
			$data['create_time']=time();
			$data['cellphone']=$data['phone'];
			if($data['status']==1){
				//修改其他地址为非默认
				M('address')->where('uid='.$data['uid'])->setField('status',0);
			}
			$result=M('address')->add($data);
			if($result){
				$info['id']=$result;
				$info['msg']='&nbsp;联系人：'.$data['realname'].'&nbsp;&nbsp;手机号:'.$data['phone'].'&nbsp;&nbsp;';
				$info['status']=1;
			}else{
				$this->error('操作失败,请重试!');
			}
			$this->ajaxReturn($info);
		}else{
			$url = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1";
			$data=$_GET;
			$uid= is_login();
			if (!$uid) {
				//cookie('__forward__', U('order', array('id'=>$data['id'],'cfrq'=>$data['cfrq'],'num'=>$data['num'])));
				cookie('__forward__', U('order', array('xlid'=>$data['xlid'],'cfdate'=>$data['cfdate'],'adult'=>$data['adult'],'child'=>$data['child'],'num'=>$data['num'],'priceid'=>$data['priceid'],'price'=>$data['price'])));
				$this->error("您还没有登陆", U("User/login"));
			}
			$cfdatearr =array_filter(explode('-', $data['cfdate']));
			$cfdate = $cfdatearr[0].'.'.$cfdatearr[1].'.'.$cfdatearr[2];
			$this->assign('cfdate',$cfdate);
			$this->assign('adult',$data['adult']);
			$this->assign('child',$data['child']);
			$url=$url.'&xl_id='.$data['xlid'];
			$line=$this->getJsonByUrl($url);
			$money = $data['price']*($data['adult']+$data['child']);
			$this->assign('money',$money);
			$addreses = M("address")->where("uid=%d and status>=0", $uid)->select();
			$this->assign("default_id", M("customer")->where("id=%d", $uid)->getField("address_id"));
			$this->assign('address', $addreses);
			if(!$line['result']){
				//无结果
				//返回错误信息
			}else{
				unset($line['result']);
				unset($line['msg']);
				$this->assign('line',$line);
			}
			$this->meta_title = '全国疗养线路';
			$this->display();
		}
	}
	/**
	 * 创建订单
	 */
	public function orderMake(){
		//避免多次调用验证
		$uid= is_login();
		if (!$uid) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$data=I('post.');
		Log::write("线路订单提交数据：".json_encode($data));
		//验证
//		num:"{:I('num')}",
//		addressid:$('#default').val(),
//		message:$('#message').val(),
//		rzsj:"{:I('cfrq')}",
//		jdid:"{:I('id')}"
		if(!$data['num']){
			$info['status']=0;
			$info['msg']='商品数量不正确!';
			$this->ajaxReturn($info);
		}
		if(!$data['addressid']){
			$info['status']=0;
			$info['msg']='未填写地址信息!';
			$this->ajaxReturn($info);
		}
		if(!$data['rzsj']){
			$info['status']=0;
			$info['msg']='请选择出发时间!';
			$this->ajaxReturn($info);
		}
		if(!$data['xl_id']){
			$info['status']=0;
			$info['msg']='商品有误,请选择其他商品!';
			$this->ajaxReturn($info);
		}
		// if(!$data['cxrname']){
		// 	$info['status']=0;
		// 	$info['msg']='请填写出行人姓名!';
		// 	$this->ajaxReturn($info);
		// }
		// if(!$data['cxrphone']){
		// 	$info['status']=0;
		// 	$info['msg']='请填写出行人电话!';
		// 	$this->ajaxReturn($info);
		// }
		//获取商品信息
		$address = M('Address')->where('id='.$data['addressid'])->find();
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1&xl_id=".$data['xl_id'];
		$goods=$this->getJsonByUrl($url);
		if(!$goods){
			$info['status']=0;
			$info['msg']='商品有误,请选择其他商品!';
			$this->ajaxReturn($info);
		}
		$order['orderid'] = date('Ym', time()) . time() . $uid;//订单号
		$order['tag']=$order['orderid'];
		$order['total_money']=$data['price'];
		$order['create_time']=time();
		$order['status']=1;
		$order['update_time']=0;
		$order['uid']=$uid;
		$order['ispay']=1;
		$order['total']=$order['total_money'];
		$order['addressid']=$data['addressid'];
		$order['message']=$data['message'];
		$order['ordertype']=3;
		$order['cftime']=str_replace("-", "", $data['rzsj']);
		$order['goodsname']=$goods['xl_code'].'-'.$goods['xl_name'];
		$order['goodspic']=$goods['xl_list_img'];
		$order['cxrname']=$address["realname"];
		$order['cxrphone']=$address['cellphone'];
		$order["crj_num"]=$data["crj_num"];
		$order["rtj_num"]=$data["rtj_num"];
		$result=M('order')->add($order);
		$goodsdata['goodid']=$goods['xl_id'];
		$goodsdata['num']=$data['num'];
		$goodsdata['orderid']=$result;
		$goodsdata['uid']=$uid;
		$goodsdata['status']=1;
		$goodsdata['create_time']=time();
		$goodsdata['price']=$data['qprice'];
		$goodsdata['total']=$data['price'];
		$goodsdata['tag']=$order['orderid'];
		$goodsdata['sname']=$goods['xl_name'];
		$goodsdata['spic']=$goods['xl_list_img'];
		$goodsdata['sid']=$goods['xl_id'];
		$goodsdata['crnum']=$data['crj_num'];
		$goodsdata['priceid']=$data['priceid'];
		$goodsdata['rtnum']=$data['rtj_num'];
		$goodsdata['stype']=3;
		M('shoplist')->add($goodsdata);
		if($result){
			$this->ts($order['orderid']);
			$info['status']=1;
			$info['orderid']=$order['orderid'];
			$info['sss']=$order;
		}else{
			$info['status']=0;
			$info['msg']='订单生成失败,请重试!';
		}
		$this->ajaxReturn($info);
	}
	/**
	 * 预订结果回调
	 */
	public function notify(){

		$data_arr=count($_POST) == 0 ? $_GET : $_POST;

		if(count($data_arr) == 0){
			$info['result'] = 0;
			$info['msg'] = '请求没有获取到。';
		}else{
			if($data_arr["order_zt"] == 2){
				$status = -1;
			}
			if($data_arr['order_zt'] == 4){
				$status = 3;
			}
			if($data_arr['order_zt'] == 5){
				$status = 6;
			}
			if($data_arr['order_id_wuxi']){
				$ore = M('Order')->where(array('orderid'=>$data_arr['order_id_wuxi']))->find();

				if($ore){
					$ore["status"] = $status;
					$ore["total"] = $data_arr["order_zje"];
					$ore["total_money"] = $data_arr["order_zje"];
					$ore["cftime"] = str_replace("-", "", $data_arr["cfrq"]);
					$ore["crj_num"] = $data_arr["crj_num"];
					$ore["rtj_num"] = $data_arr["rtj_num"];
					$ore["order_beizhu"] = $data_arr["order_beizhu"];

					M('Order')->save($ore);

					$info['result'] = 1;
					$info['msg'] = 'SUCCESS';
				}else{
					$info['result'] = 0;
					$info['msg'] = '没有查找到订单信息';
				}
			}
		}
		Log::write("线路回调数据：".json_encode($data_arr));
		$this->ajaxReturn($info);
	}
	
	public function orderdetail(){
		$data = I('get.');
		$url = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1&xl_id=".$data['xl_id'];
		$data['line']=$this->getJsonByUrl($url);
		$address = M('Address')->where('id='.$data['addressid'])->find();
		$data['lxr'] = $address['realname'];
		$data['lxrphone'] = $address['cellphone'];
		$money = $data['price'];
		$this->assign('money',$money);
		$this->assign('data',$data);
		$this->display();
	}
	
	/*****全部订单****/
	public function allorder() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();

		//自动发货
//		$this->auto_express($uid);
		
		/* 数据分页*/
		$map['uid']   = $uid;
		$map['ordertype'] = 3;
		$map['total'] = array ('gt', 0);;
		$list = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);//
		$this->meta_title = '我的所有订单';
		$this->display();
	}
	/* 待支付订单*/
	public function needpay() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}

		$uid = is_login();
		//自动发货
		//$this->auto_express($uid);
		
		/* 数据分页*/
		$map['uid']    = $uid;
		$map['ordertype'] = 3;
		$map['status'] = -1;
		$map['ispay']  = 1;
		$list          = D("order")->getLists($map);
		if (empty($list)) {
			$list = array ();
		}
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->meta_title = '待支付订单';
		$this->display();
	}

	/* 待发货订单*/
	public function tobeshipped() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
		
		//自动发货
		//$this->auto_express($uid);
		/* 数据分页*/
		$map['uid']    = $uid;
		$map['ordertype'] = 3;
		$map['status'] = 1;
		$list          = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->assign('list', $list);
		$this->meta_title = '待发货订单';
		$this->display();
	}

	/* 待确认订单*/
	public function tobeconfirmed() {
		if (!is_login()) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$uid = is_login();
//		//自动发货
//		$this->auto_express($uid);
		
		$map['uid']    = $uid;
		$map['ordertype'] = 3;
		$map['status'] = 2;
		$list          = D("order")->getLists($map);
		$this->assign('list', $list);// 赋值数据集
		$page = D("order")->getPage($map);
		$this->assign('page', $page);// 
		$this->meta_title = '待确认订单';
		$this->display();
	}
	/**
	 * 线路和酒店订单推送
	 */
	private function ts($orderid){
		$order=M('Order')->where(array('orderid'=>$orderid))->find();
		if($order['ordertype']!=1){
			$goods=M('shoplist')->where(array('tag'=>$order['tag']))->find();
			$address=M('address')->where(array('id'=>$order['addressid']))->find();
			if($goods['stype'] == 2){
				$crnum=$goods['num'];
				$rtnum=0;
			}
			if($goods['stype'] == 3){
				$crnum=$goods['crnum'];
				$rtnum=$goods['rtnum'];
			}
			$url = "http://www.myvacation.cn/wuxi/api.php?Action=OrderPost".
			"&xl_id=".$goods['sid'].
			"&jg_id=".$goods['priceid'].
			"&cfrq=".$order['cftime'].
			"&xlmc=".urlencode($goods['sname']).
			"&crj_num=".$crnum.
			"&rtj_num=".$rtnum.
			"&tssm=".urlencode($order['message']).
			"&order_zje=".floatval($order['total_money']).
			"&order_id_wx=".$order['orderid'].
			"&lxr=".urlencode($address['realname']).
			"&mobile=".$address['cellphone'];
			$zfre=$this->getJsonByUrl($url);
			Log::write("线路提交订单请求：".$url);
			Log::write("线路提交订单返回数据：".json_encode($zfre));
			if($zfre['result'] == 1){
				$ore = M('Order')->where(array('orderid'=>$orderid))->setField('ists',1);
				$ore1 = M('Order')->where(array('orderid'=>$orderid))->setField('tsorderid',"XZ0".$zfre['order_id']);
			}
		}
	}
}
