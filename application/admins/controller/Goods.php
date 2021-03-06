<?php
/**
* 商品管理
*/
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Goods extends BaseAdmin{
	// 商品列表
	public function index(){
		$data['pageSize'] = 15;
		$data['page'] = max(1,(int)input('get.page'));

		$data['wd'] = trim(input('get.wd'));
		$where = array();
		$data['wd'] && $where = 'title like "%'.$data['wd'].'%"';
		$data['data'] = $this->db->table('goods')->where($where)->order('gid desc')->pages($data['pageSize']);

		$label_ids = [];
		foreach ($data['data']['lists'] as $item) {
			!in_array($item['type_id'],$label_ids) && $label_ids[] = $item['type_id'];
		}
		$label_ids && $data['labels'] = $this->db->table('goods_label')->where('id in('.implode(',',$label_ids).')')->cates('id');
		$this->assign('data',$data);
		return $this->fetch();
	}

	// 添加商品
	public function add(){
		$data['type'] = $this->db->table('goods_label')->where(array('flag'=>'type'))->lists();

		$gid = (int)input('get.gid');

		$data['item'] = $this->db->table('goods')->where(array('gid'=>$gid))->item();

		$this->assign('data',$data);
		return $this->fetch();
	}

	public function save(){
		$gid = (int)input('post.gid');
		$data['title'] = trim(input('post.title'));
		$data['type_id'] = (int)input('post.type_id');
		$data['img'] = trim(input('post.img'));
		$data['price'] = trim(input('post.price'));
		$data['stock'] = trim(input('post.stock'));
		$data['desc'] = trim(input('post.desc'));
		$data['status'] = (int)input('post.status');

		if($data['title'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'商品名称不能为空')));
		}
		if($gid){
			$this->db->table('goods')->where(array('gid'=>$gid))->update($data);
		}else{
			$data['add_time'] = time();
			$this->db->table('goods')->insert($data);
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}

	// 图片上传
	public function upload_img(){
		$file = request()->file('file');
		if($file==null){
			exit(json_encode(array('code'=>1,'msg'=>'没有文件上传')));
		}
		$info = $file->move(ROOT_PATH.'public'.DS.'uploads');
		$ext = ($info->getExtension());
		if(!in_array($ext,array('jpg','jpeg','gif','png'))){
			exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
		}
		$img = '/uploads/'.$info->getSaveName();
		exit(json_encode(array('code'=>0,'msg'=>$img)));
	}

	// 删除
	public function delete(){
		$gid = (int)input('post.gid');
		$this->db->table('goods')->where(array('gid'=>$gid))->delete();
		exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
	}
}
