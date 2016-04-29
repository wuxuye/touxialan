<?php
/**
 * 标签信息相关后台Model
 * by wuxuye 2016-04-28
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class TagsModel extends ViewModel{

	private $tags_table = '';
	private $goods_tag_relate_table = '';

	protected function _initialize(){
		$this->tags_table = C("TABLE_NAME_TAGS");
		$this->goods_tag_relate_table = C("TABLE_NAME_GOODS_TAG_RELATE");
	}

	/**
	 * 标签列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getTagsList($where = array(),$page = 1,$num = 20){
		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = array();
		$result['count'] = 0;

		//基本条件
		$where['tags.state'] = C("STATE_TAGS_NORMAL");

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->tags_table." as tags")
				->field("tags.*")
				->where($where)
				->limit($limit)
				->order("tags.id DESC")
				->select();

		$result['list'] = $list;

		//数量获取
		$count = M($this->tags_table." as tags")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}


	/**
	 * 标签关联商品
	 * $param int $goods_id 商品id
	 * @param array $tag_list 标签列表
	 * @result array $result 结果返回
	 */
	public function relateGoods($goods_id = 0,$tag_list = array()){

		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";

		//检查商品状态
		$goods_info = array();
		$goods_id  = intval($goods_id);
		$goods_obj = new \Yege\Goods();
		$goods_obj->goods_id = $goods_id;
		$goods_info = $goods_obj->getGoodsInfo();

		if($goods_info['state'] == 1 && !empty($goods_info['result']['goods_id'])){
			$goods_info = $goods_info['result'];
			if($goods_info['goods_state'] == C("STATE_GOODS_NORMAL")){
				//拿到已有标签
				$goods_tags = $goods_tags_list = array();
				$tag_obj = new \Yege\Tag();
				$goods_tags = $tag_obj->getTagsListByGoodsId($goods_info['goods_id']);

				foreach($goods_tags as $key => $val){
					$goods_tags_list[] = $val['id'];
				}
				//拿到需要添加的标签
				$tag_all = $tag_all_list = $where = array();
				if(!empty($tag_list) && is_array($tag_list)){
					$where['state'] = C("STATE_TAGS_NORMAL");
					$where['id'] = array('in',$tag_list);
					$tag_all = M($this->tags_table)->where($where)->select();
					foreach($tag_all as $key => $val){
						$tag_all_list[] = $val['id'];
					}
				}

				//拿到要删除的标签
				$delete_list = array();
				foreach($goods_tags_list as $key => $val){
					if(!in_array($val,$tag_all_list)){
						$delete_list[] = $val;
					}
				}

				//删掉标签
				if(!empty($delete_list)){
					$where = array();
					$where['goods_id'] = $goods_id;
					$where['tag_id'] = array('in',$delete_list);
					M($this->goods_tag_relate_table)->where($where)->delete();
				}

				//逐个添加标签
				foreach($tag_all_list as $key => $val){
					$temp = $where = array();
					$where['goods_id'] = $goods_id;
					$where['tag_id'] = $val;
					$temp = M($this->goods_tag_relate_table)->where($where)->find();
					if(empty($temp)){
						$add = array();
						$add['goods_id'] = $goods_id;
						$add['tag_id'] = $val;
						M($this->goods_tag_relate_table)->add($add);
					}
				}

				$result['state'] = 1;
				$result['message'] = "操作成功";

			}else{
				$result['message'] = "商品不在正常状态";
			}
		}else{
			$result['message'] = "商品数据未能获取";
		}

        return $result;
    }

}
