<?php
/************************************************************
** @Description: 标签
** @Author: george
** @Date:   2018-04-11 16:14:00
** @Last Modified by:   george
** @Last Modified time: 2018-04-11 16:16:02
*************************************************************/

class Tags extends MY_Controller
{

	protected $model_name;

	public function __construct()
	{
		parent::__construct();
		$this->model_name = "tags_model";
	}

	public function index()
	{
		$this->data['page_title'] = 'Tags列表';
		$this->display('admin/tags_list.html');
	}

	public function table()
	{
		$post = $this->input->post();
        $post['status|='] = 0;
		$this->query($post);
	}

	public function listDataFormat($data)
    {
        if(empty($data)) return [];
        foreach ($data['data'] as $key => $value) {
        	$data['data'][$key]['status'] = $value['status']==1 ? '暂停':'正常';
        	$data['data'][$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
        	$data['data'][$key]['img_src'] = '<img src="'.$value['img_src'].'">';
        }
        return $data;
    }

    public function add()
    {
    	$this->data['page_title'] = '新增tags';
    	$this->display('admin/tags_add.html');
    }

    public function edit()
    {
    	$this->load->model($this->module.'/'.$this->model_name);
    	$model = $this->model_name;
    	$id = $this->input->get('id');
    	$tags = $this->$model->getConditionData("*",'id='.(int)$id);
    	$this->data['tags'] = $tags[0];
    	$this->data['page_title'] = '编辑tags';
    	$this->display('admin/tags_edit.html');
    }

    public function del(){
    	$this->load->model($this->module.'/'.$this->model_name);
    	$model = $this->model_name;
    	#判断tags下有无内容
    	$id = $this->input->post('id');
    	$data['id'] = (int)$id;
    	$data['status'] = 1;
    	$result = $this->$model->editData($data,'id='.(int)$id);

    	if (!$result) {
    		$this->ajaxReturn($result,300,'删除失败');
    	}else{
    		$this->ajaxReturn($result);
    	}
    }

    public function save()
    {
    	$this->load->model($this->module.'/'.$this->model_name);
    	$model = $this->model_name;
    	$form_data = $this->input->post();
    	unset($form_data['myfile']);
        if (isset($form_data['id'])) {
        	$result = $this->$model->editData($form_data,'id='.(int)$form_data['id']);
        }else
        {
        	$result = $this->$model->addData($form_data);
        }

        if($result===false || $result<1)
        {
        	$this->ajaxReturn($result,300,'保存失败');
        }else
        {
        	$this->ajaxReturn($result);
        }
    }

}