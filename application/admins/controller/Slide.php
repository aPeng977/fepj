<?php
/**
* 幻灯片管理
*/
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

class Slide extends BaseAdmin{

	// 首页首屏
	public function index(){
		$data = $this->db->table('slide')->where()->lists();
		$this->assign('data',$data);
		return $this->fetch();
	}


	// 添加首屏
	public function add_first(){
		$id = (int)input('get.id');
		$slide = $this->db->table('slide')->where(array('id'=>$id))->item();
		$this->assign('data',$slide);
		return $this->fetch();
	}

	public function save(){
		$id = (int)input('post.id');
		$data['ord'] = (int)input('post.ord');
		$data['title'] = trim(input('post.title'));
		$data['url'] = trim(input('post.url'));
		$data['img'] = input('post.img');
		$data['location'] = trim(input('post.location'));
		if($data['title']=='' || $data['url']=='' || $data['img']==''){
			exit(json_encode(array('code'=>1,'msg'=>'数据不能为空')));
		}
		if($id>0){
			$this->db->table('slide')->where(array('id'=>$id))->update($data);
		}else{
			$this->db->table('slide')->insert($data);
		}

		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
	}

	// 删除
	public function delete(){
		$id = (int)input('post.id');
		$this->db->table('slide')->where(array('id'=>$id))->delete();
		exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
	}
}
