<?php
/**
 * 关键词相关前台Model
 * by wuxuye 2016-06-20
 */
namespace Home\Model;
use Think\Model\ViewModel;

class KeywordModel extends ViewModel{

	private $keyword_table = '';

	protected function _initialize(){
		$this->keyword_table = C("TABLE_NAME_SEARCH_KEYWORD");
	}

	/**
	 * 关键词记录
	 * @param string $keyword 关键词
	 * @return array $result 分词结果返回
	 */
	public function saveKeyword($keyword = ""){

		$result = [
			"keyword_list" => [],
			"keyword" => "",
		];

		//太频繁的不记录
		$last_time = cookie("keyword_wait");
		//5秒延迟
		if(($last_time + 5) < time()){
			//用空格拆词
			$word = explode(" ",$keyword);
			if(count($word) > C("HOME_GOODS_MAX_PARTICIPLE_NUM")){
				$temp = [];
				for($i = 0;$i < C("HOME_GOODS_MAX_PARTICIPLE_NUM");$i++){
					$temp[] = $word[$i];
				}
				if(!empty($temp)){
					$word = $temp;
				}
			}
			//数据过滤
			//$word = $this->filterWord($word);
			if(!empty($word)){
				$result['keyword_list'] = $word;
				$result['keyword'] = implode(" ",$word);
				//数据添加
				foreach($word as $key => $val){
					$info = $where = [];
					$where['keyword'] = $val;
					$info = M($this->keyword_table)->where($where)->find();
					if(!empty($info['id'])){
						$save = $where = [];
						$where['id'] = $info['id'];
						$save['count'] = ["exp","count+1"];
						$save['updatetime'] = time();
						M($this->keyword_table)->where($where)->save($save);
					}else{
						$add = [];
						$add['keyword'] = $val;
						$add['inputtime'] = $add['updatetime'] = time();
						M($this->keyword_table)->add($add);
					}
				}
				cookie("keyword_wait",time(),20);
			}
		}

		return $result;
	}

	/**
	 * 过滤词汇
	 * @param array $word 待过滤数组
	 * @return array $word 结果返回
	 */
	private function filterWord($word = []){
		$filter = C("HOME_GOODS_LIST_KEYWORD_FILTRATION_LIST");

		if(!empty($filter)){
			foreach($word as $key => $val){
				$val = check_str($val);
				if(in_array($val,$filter) || $val == ""){
					unset($word[$key]);
				}
			}
		}

		return $word;
	}

}
