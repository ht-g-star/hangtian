<?php
/**
 * 度假酒店功能
 */
namespace Wechat\Controller;
use Home\Controller\HomeController;
use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Log;

class HotelController extends AuthController {

	

	/**
	 * 酒店列表信息
	 */
	public function index() {
		$this->meta_title = '酒店预订';
		//获取城市
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetHPackageSections";
		$area=$this->getJsonByUrl($url);
		unset($area['result']);
		unset($area['msg']);
		$this->assign('area',$area);
		$city=array();
		foreach ($area as $k => $v) {
			foreach ($v['city'] as $kk => $vv) {
				$vv['sq_id']=$v['section_id'];
				array_push($city,$vv);
			}
		}
		$this->assign('city',$city);
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetHPackagelist";
		$data=$_GET;
		$data['type'];
		if($data['type']){
			$url=$url.'&type='.$data['type'];
		}else{
			$url=$url.'&type=2';
		}
		if($data['xl_type']){
			$url=$url.'&xl_type='.$data['xl_type'];
		}else{
			$url=$url.'&type=3';
		}
		if($data['city_id']){
			$url=$url.'&city_id='.$data['city_id'];
			$this->assign('cityid',$data['city_id']);
		}else{
			$url=$url.'&city_id=';
		}
		if($data['section_id']){
			$url=$url.'&section_id='.$data['section_id'];
		}else{
			$url=$url.'&section_id=';
		}
		if($data['keyword']){
			$url=$url.'&keywords='.$data['keyword'];
		}
		
		$cache=$this->getJsonByUrl($url.'&show_count=0');
		$count=$this->getJsonByUrl($url.'&show_count=1');
		if(!$cache['result']){
			//无结果
			//返回错误信息
		}else{
			unset($cache['result']);
			unset($cache['msg']);
			$this->assign('cache',$cache);
		}
		$this->display();
	}
	/**
	 * 详情页面
	 */
	public function detail(){
		$this->meta_title = '酒店预订';
		$id=I('id');
		if($id){
			$url="http://www.myvacation.cn/wuxi/api.php?Action=GetHPackageDetail&xl_id=".$id;
			$cache=$this->getJsonByUrl($url);
			$this->assign('cache',$cache);
			
			$url1 = "http://www.myvacation.cn/m/members/action/get_xl_jiage_string_wuxi.php?xl_id=".$id;
			$lineinfo=$this->getJsonByUrl1($url1);
			Log::write("酒店套餐1：".json_encode($lineinfo));
			$lineinfo =array_filter(explode(';', $lineinfo));

			Log::write("酒店套餐2：".json_encode($lineinfo));
			foreach($lineinfo as $k=>$v){
				$lineinfo[$k] = array_filter(explode('|', $v));
			}
			Log::write("酒店套餐3：".json_encode($lineinfo));
			$this->assign('lineinfocount',count($lineinfo));
			$this->assign('lineinfo',$lineinfo);
			

			$this->display();
		}else{
			$this->error('此酒店套餐不存在!');
		}
	}
	/**
	 * 下单页面
	 */
	public function order(){
		$this->meta_title = '订单结算';
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
			//验证
			$id=I('id');
			$data = I('get.');
			$uid= is_login();
			if (!$uid) {
				//cookie('__forward__', U('order', array('id'=>$data['id'],'cfrq'=>$data['cfrq'],'num'=>$data['num'])));
				cookie('__forward__', U('order', array('id'=>$id,'cfrq'=>$data['cfrq'],'num'=>$data['num'])));
				$this->error("您还没有登陆", U("Public/login"));
			}
			if($id){
				$url="http://www.myvacation.cn/wuxi/api.php?Action=GetHPackageDetail&xl_id=".$id;
				$cache=$this->getJsonByUrl($url);
				$cache['yfze']=$data['price']*$data['num'];
				$this->assign('cache',$cache);
				$addreses = M("address")->where("uid=%d and status>=0", $uid)->select();
				$this->assign("default_id", M("customer")->where("id=%d", $uid)->getField("address_id"));
				$this->assign('address', $addreses);

				$total = $cache["xl_qijia"] * $data["num"];
				$this->assign("total", $total);
				$this->display();
			}else{
				
			}
		}
	}
	/**
	 * 创建订单
	 */
	public function orderMake(){
		//避免多次调用验证
		$uid= is_login();

		$openid =  get_openid(I('openid'));


		if (!$uid) {
			$this->error("您还没有登陆", U("Public/login"));
		}
		$data=I('post.');
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
		//获取商品信息
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetHPackageDetail&xl_id=".$data['xl_id'];
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
		$order['ordertype']=2;
		$order['cftime']=str_replace("-", "", $data['rzsj']);
		$order['goodsname']=$goods['xl_code'].'-'.$goods['xl_name'];
		$order['goodspic']=$goods['xl_list_img'];
		$order["crj_num"] = $data["num"];
		$order["rtj_num"] = 0;
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
		$goodsdata['priceid']=$data['priceid'];
		$goodsdata['stype']=2;
		M('shoplist')->add($goodsdata);
		if($result){
			$this->ts($order['orderid']);
			$info['status']=1;
			$info['msg']='订单创建成功,请等待网站审核后付款!';
			$info['orderid']=$order['orderid'];
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
		$data = file_get_contents("php://input");
		file_put_contents('hotel_notify.txt','响应参数:'.date('Y-m-d H:i:s').PHP_EOL.'通知信息:'.$data.PHP_EOL.PHP_EOL.PHP_EOL,FILE_APPEND);
		if($data['order_zt'] == 4){
			$status = 1;
		}
		if($data['order_zt'] == 5){
			$status = 6;
		}
		$ore = M('Order')->where(array('orderid'=>$data['order_id_wuxi']))->setField('status',$status);
		$info['result'] = 1;
		$info['msg'] = 'SUCCESS';
		$this->ajaxReturn($info);
	}
	
	//修改地址
	public function addset(){
		if(IS_POST){
			$data=I('post.');
//			dump($data);	
			$add=M('address')->where(array('id'=>$data['addid']))->find();
			if(!$add){
				$info['status']=0;
				$info['msg']='未找到地址!';
				$this->ajaxReturn($info);
			}else{
				$info['status']=1;
				$info['msg']='找到了！';
				$info['add']=$add;
				$this->ajaxReturn($info);
			}			
		}else{
			die('no access!');
		}
	}
	
	//保存地址
	public function addsave(){
		if(IS_POST){
			$data=I('post.');
//			dump($data);	
			$add=M('address')->where(array('id'=>$data['id']))->find();
			if(!$add){
				$info['status']=0;
				$info['msg']='未找到地址!';
				$this->ajaxReturn($info);
			}else{
				$re=M('address')->save($data);
				if($re!==FALSE){
					$info['status']=1;
					$info['msg']='修改成功！';
					
				}else{
					$info['status']=0;
					$info['msg']='修改失败，请重试！';
				}				
				$this->ajaxReturn($info);
			}			
		}else{
			die('no access!');
		}
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
			Log::write("酒店提交订单请求：".$url);
			Log::write("酒店提交订单返回数据：".json_encode($zfre));
			if($zfre['result'] == 1){
				$ore = M('Order')->where(array('orderid'=>$orderid))->setField('ists',1);
				$ore1 = M('Order')->where(array('orderid'=>$orderid))->setField('tsorderid',"XZ0".$zfre['order_id']);
			}
		}
	}
}
