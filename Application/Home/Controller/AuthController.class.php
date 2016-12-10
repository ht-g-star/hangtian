<?php
/**
 * 旅游线路和酒店信息接口调用
 */
namespace Home\Controller;
use Think\Log;
class AuthController extends HomeController {
	/**
	 * 初始化
	 */
	protected function _initialize() {
		$this->auth_token();
	}
	/**
	 * 鉴权
	 */
	private function auth_token($hard = 0){
		if($hard == 0){
			$tokenfile='auth_token';
			$auth_interval=60*60*12;//鉴权间隔时间 单位:秒 间隔:12小时
			$auth_token = file_get_contents($tokenfile,null,null,0,10);
			Log::write("验证是否有时间：".$auth_token);
			$nowtime=time();
			if($auth_token){
				//超过设定时间重新鉴权
				if(($nowtime-$auth_token)>=$auth_interval){
					//鉴权操作
					$result=$this->auth_opt();
					file_put_contents($tokenfile, $nowtime);
					return $result;
				}else{
					return $auth_token;
				}
			}else{
				$result=$this->auth_opt();
				//鉴权操作
				file_put_contents($tokenfile, $nowtime);
				return $result;
			}
		}else{
			$tokenfile='auth_token';
			$auth_interval=60*60*12;//鉴权间隔时间 单位:秒 间隔:12小时
			$result=$this->auth_opt();
			$nowtime=time();
			if($result["result"] == true){
				file_put_contents($tokenfile, $nowtime);
			}
			return $result;
		}
	}
	/**
	 * 鉴权操作
	 */
	private function auth_opt(){
		M('travel_cache')->delete();
		$url="http://www.myvacation.cn/wuxi/api.php?Action=Auth&uid=wx738&psw=dy3m7g";
		$result=$this->getJsonByUrl1($url);

		Log::write("酒店/线路接口认证：".json_encode($result));

		return $result;
	}
	/**
	 * Get请求获取JSON 转Array
	 * @param $url 请求地址(附带参数)
	 * @param return 数组对象
	 */
	protected function getJsonByUrl($url){
		//缓存机制
//		$cache=M('travel_cache')->where(array('requesturl'=>$url))->find();
//		if($cache){
//			$result=$cache['result'];
//		}else{
			$result=file_get_contents($url);
			$result = iconv("GBK", "utf-8//IGNORE",$result);
			$result = json_decode($result,true);

			if($result["result"] == false){
				$this->auth_token(1);
				$result=file_get_contents($url);
				$result = iconv("GBK", "utf-8//IGNORE",$result);
				$result = json_decode($result,true);
				Log::write("测试数据5：".json_encode($result));
				Log::write("测试数据5：".json_encode($url));
			}



//			M('travel_cache')->add(array('requesturl'=>$url,'result'=>$result,'ctime'=>time()));
//		}
		return  $result;
	}
	
	protected function getJsonByUrl1($url){
		//缓存机制
		$result=@file_get_contents($url);
		$result = iconv("GBK", "utf-8//IGNORE",$result);
		return $result;
	}

	
	/**
	 * 分页
	 * 模板页分页条变量为$pageshow
	 * @param $arr 分页数据
	 * @param $count 数据总数
	 * @param $pageNo 页数
	 * @param $pageSize 每页数据数量
	 * @return 返回当前页数据
	 */
	public function getPage($arr=array(),$count=0,$pageNo=1,$pageSize=10){
		$pageNo=$pageNo?$pageNo:1;
		unset($cache['result']);
		unset($cache['msg']);
		$result=array();
		for ($i=$pageSize*($pageNo-1); $i < $pageSize*($pageNo-1)+$pageSize; $i++) {
			if(isset($arr[$i])){
				array_push($result,$arr[$i]);
			} 
		}
		//分页条
		$pageshow="<ul data-am-widget='pagination' class='am-pagination am-pagination-default'>";
		$pageshow=$pageshow."<li class='am-pagination-first '><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>1))."' class=''>第一页</a></li>";
		$pageshow=$pageshow."<li class='am-pagination-prev '><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>$pageNo-1))."' class=''>上一页</a></li>";
		for ($i=1; $i < $count/$pageSize+1; $i++) {
			if($pageNo==$i){
				$pageshow=$pageshow."<li class='am-active'><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>$i))."' class=''>".$i."</a></li>";
			}else{
				$pageshow=$pageshow."<li><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>$i))."' class=''>".$i."</a></li>";
			}
		}
		$pageshow=$pageshow."<li class='am-pagination-next '><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>$pageNo+1))."' class=''>下一页</a></li>";
		$pageshow=$pageshow."<li class='am-pagination-last '><a href='".U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,array('p'=>floor($count/$pageSize)+1))."' class=''>最末页</a></li>";
		$pageshow=$pageshow."</ul>";
		$this->assign('pageshow',$pageshow);
		return $result;
	}
}