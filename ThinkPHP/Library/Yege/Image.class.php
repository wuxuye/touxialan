<?php
/**
 * 图片处理类
 *
 * 提供方法
 * upload_image		上传图片方法
 * zoom_image		图像处理方法(等比缩放)
 */
namespace Yege;
class Image{
	
	/**
	 * 默认上传配置
	 */
    private $config = array(
        'maxSize'       =>  3145728, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    	'rootPath'      =>  'Uploads/', //附件上传根目录
        'savePath'      =>  '', //设置附件上传（子）目录
    	'folder'		=>  'goods_images'//存图片的文件夹
    );


    public function __construct($config = array()){
    	header("Content-Type: text/html; charset=utf-8");
    	
    	$this->config = array_merge($this->config, $config);//替换掉相关参数
    	
        $this->image_obj = new \Think\Image();//图像类 
		$this->upload_obj = new \Think\Upload();//上传类
		
		//用配置中的参数对上传类进行设置
		$this->upload_obj->maxSize   =     $this->config['maxSize'];// 设置附件上传大小
		$this->upload_obj->exts      =     $this->config['exts'];// 设置附件上传类型
		$this->upload_obj->rootPath  =     $this->config['rootPath']; // 设置附件上传根目录
		$this->upload_obj->savePath  =     $this->config['savePath'].$this->config['folder'].'/'; // 设置附件上传（子）目录
		
    }

    /**
     * 上传图片方法
     * @param array $image 文件数组
     * @param string $folder 指定文件夹
	 * @param string $saveName 保存文件名
     * @return array $result 上传成功后返回一个数组
     * 					   url => 上传文件的全路径
     * 					   file_name => 上传文件的文件名
     * 					   save_path => 上传文件的存储路径
     */
	public function upload_image($image,$folder="",$saveName = ""){

		if(!empty($saveName)){
			$this->upload_obj->fileName = $saveName;
		}

		//如果有指定的文件夹就修改配置
		if(!empty($folder)){
			$this->upload_obj->savePath = $this->config['savePath'].$folder.'/'; // 设置附件上传（子）目录
		}
		
		$info = $result = array();
		// 上传文件 
		$info = $this->upload_obj->uploadOne($image);

		//上传成功 开始组装 数组返回
		if(!empty($info['savename'])){
			$result['url'] = $this->config['rootPath'].$info['savepath'].$info['savename'];
			$result['file_name'] = $info['savename'];
			$result['save_path'] = $this->config['rootPath'].$info['savepath'];
		}else{
			$wrong = array();
			$wrong = $this->upload_obj -> getError();
			$result['wrong'] = $wrong;
		}
		return $result;
	}
	
	/**
	 * 图像处理方法(等比缩放)
	 * @param string $now_image 图片原路径
	 * @param string $width 图片的宽
	 * @param string $height 图片的高
	 * @param string $path 生成图片的存储路径(不带文件名)
	 * @param string $prefix 自定义前缀
	 * @return array $result state => 0 上传失败,-1宽、高为空,-2文件未找到,1成功
	 * 						(当state为1时,还会带一个url表示生成图片的全路径)
	 */
	public function zoom_image($now_image="",$width=0,$height=0,$path="",$prefix=""){
		$result = array();
		$result['state'] = 0;
		$file_info = pathinfo($now_image);
		//获取文件信息
		if(!empty($file_info['basename'])){
			//获取宽高
			if(!empty($width) && !empty($height)){
				//没这个参数就生成到和原图一样的目录下
				if(empty($path)){
					$path = $file_info['dirname'];
				}
				//没这个参数就用宽x高的格式
				if(empty($prefix)){
					$prefix = $width."x".$height."_";
				}
				$this->image_obj->open($now_image);
				$this->image_obj->thumb($width, $height)->save($path."/".$prefix.$file_info['basename']);
				if(file_exists($path."/".$prefix.$file_info['basename'])){
					$result['state'] = 1;
					$result['url'] = $path."/".$prefix.$file_info['basename'];
				}
			}else{
				$result['state'] = -1;
			}
		}else{
			$result['state'] = -2;
		}
		return $result;
	}
}
