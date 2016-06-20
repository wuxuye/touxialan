<?php
/**
 * 数据统计相关后台Model
 * by wuxuye 2016-06-17
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class StatisticsModel extends ViewModel{

	private $attr_table = '';
	private $goods_table = '';
	private $statistics_attr_table = '';
	private $statistics_tag_table = '';

	protected function _initialize(){
		$this->attr_table = C("TABLE_NAME_ATTR");
		$this->goods_table = C("TABLE_NAME_GOODS");
		$this->statistics_attr_table = C("TABLE_NAME_STATISTICS_ATTR");
		$this->statistics_tag_table = C("TABLE_NAME_STATISTICS_TAG");
	}

	/**
	 * 获取属性统计
	 * @return array $result 结果返回
	 */
	public function getAttrStatistics(){
		$result = [];

		//首先获取所有统计结果
		$list = [];
		$list = M($this->statistics_attr_table)->select();

		//数据处理
		foreach($list as $attr){
			$result[$attr['attr_id']] = $attr;
		}

		return $result;
	}

	/**
	 * 属性统计逻辑
	 * @return array $result 结果返回
	 */
	public function statisticsAttr(){
		$result = ['state'=>0,'message'=>'未知错误'];

		//首先获取所有正常出售中的商品
		$list = $where = [];
		$where['state'] = C("STATE_GOODS_NORMAL");
		$where['is_shop'] = 1;
		$list = M($this->goods_table)->field("id,attr_id")->where($where)->select();

		//开始数量统计
		$count = [];
		foreach($list as $key => $val){
			if(!empty($val['attr_id'])){
				if(empty($count[$val['attr_id']])){
					$count[$val['attr_id']] = 0;
				}
				$count[$val['attr_id']] ++;
			}
		}

		$wrong = 0;

		//再将属性数据更新至表中
		foreach($count as $key => $val){
			$temp = $where = [];
			$where['attr_id'] = $key;
			$temp = M($this->statistics_attr_table)->where($where)->find();
			if(!empty($temp['id'])){
				//数据更新
				$save = $where = [];
				$save['goods_num'] = $val;
				$save['statistics_time'] = time();
				$where['id'] = $temp['id'];
				if(M($this->statistics_attr_table)->where($where)->save($save)){
					//统计成功
				}else{
					$wrong ++;
					add_wrong_log("属性id (attr_id) 的商品数量统计失败，当时欲更新 属性统计表中 id 为 ".$temp['id']."的数据，\r\n参数 goods_num：".$save['goods_num']." statistics_time：".$save['statistics_time']);
				}
			}else{
				//数据添加
				$add = [];
				$add['attr_id'] = $key;
				$add['goods_num'] = $val;
				$add['statistics_time'] = time();
				if(M($this->statistics_attr_table)->add($add)){
					//统计成功
				}else{
					$wrong ++;
					add_wrong_log("属性id (attr_id) 的商品数量统计失败，当时欲添加 数据至 属性统计表中，\r\n参数 attr_id：".$add['attr_id']." goods_num：".$add['goods_num']." statistics_time：".$add['statistics_time']);
				}
			}
		}


		if(empty($wrong)){
			$result['state'] = 1;
			$result['message'] = "统计成功";
		}else{
			$result['message'] = "存在失败数据 ".$wrong." 条，详情见错误日志";
		}

		//删掉过期统计(5分钟前)
		$where = [];
		$where['statistics_time'] = ['lt',time()-5*60];
		M($this->statistics_attr_table)->where($where)->delete();

		return $result;
	}

}
