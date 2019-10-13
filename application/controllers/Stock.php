<?php defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends MY_Controller
{

    public function __construct()
    {
		parent::__construct();
    }
    public function index()
    {
        $data = [
            'dataTableListUrl' => '/ERPAccounting/stock/getList',
            'openDialogUrl' => '/ERPAccounting/stock/openDialog',
            'saveDialogUrl' => '/ERPAccounting/stock/saveDialog',
            'delUrl' => '/ERPAccounting/stock/delOne',
		];
		// $this->mockadd();
        $this->layout->view('stockView', $data);
    }

    public function getList()
    {
		//dataTable basic setting
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$colums = $this->input->post('columns');
		$order = $this->input->post('order');
		$order_str = $this->searchOrderBy($colums, $order);
		//search data condition
		$search = [
			"id" => $this->input->post('id'),
			"name" => $this->input->post('name'),
			"unit" => $this->input->post('unit'),
			"now_stock" => $this->input->post('now_stock'),
			"safe_stock" => $this->input->post('safe_stock'),
			// "total_stock" => $this->input->post('total_stock'),
			"warning_stock" => $this->input->post('warning_stock'),
			"avg_price" => $this->input->post('avg_price'),
			"sale_price" => $this->input->post('sale_price'),
			"start_time" => $this->input->post('start_time'),
			"end_time" => $this->input->post('end_time'),
		];
		$search['warning_stock'] = $search['warning_stock'] !== 'false' ? $search['warning_stock'] : '';
		$this->load->model('Stock_model');
		list($count,$list) = $this->Stock_model->get_list($start,$length,$order_str,$search);
		$data = [
			'data' => $list,
			'count' => $count,
		];
		echo json_encode($data);
		return;
	}

	public function openDialog(){
		$id = $this->input->get("id");
		$data = [];
		if(!empty($id)){
			$this->load->model('Stock_model');
			$data['edit_data'] = $this->Stock_model->get_one($id);
		}

		$this->load->view('dialog/stockDialogView', $data);
	}

	public function saveDialog(){
		$this->load->model('Stock_model');
		$id = $this->input->post("id");
		if(empty($id)){
			$affected_rows = $this->Stock_model->add_one([
				"name" => $this->input->post('name'),
				"unit" => $this->input->post('unit'),
				"now_stock" => (double) $this->input->post('now_stock'),
				"safe_stock" => (double) $this->input->post('safe_stock'),
				// "total_stock" => $this->input->post('total_stock'),
				"avg_price" => (double) $this->input->post('avg_price'),
				"sale_price" => (double) $this->input->post('sale_price'),
				"create_time" => date(config_item("log_date_format")),
			]);
		}else{
			$affected_rows = $this->Stock_model->edit_one($id,[
				"name" => $this->input->post('name'),
				"unit" => $this->input->post('unit'),
				"now_stock" => $this->input->post('now_stock'),
				"safe_stock" => $this->input->post('safe_stock'),
				// "total_stock" => $this->input->post('total_stock'),
				"avg_price" => $this->input->post('avg_price'),
				"sale_price" => $this->input->post('sale_price'),
			]);
		}
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
		return;
	}

	public function delOne(){
		$this->load->model('Stock_model');
		$id = $this->input->post("id");
		$affected_rows = $this->Stock_model->del_one($id);
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
		return;
	}
	
	public function mockadd(){
		$this->load->model('Stock_model');
		for ($i=0; $i < 10000; $i++) { 
			$this->Stock_model->add_one([
				"name" => '我是誰'.$i,
				"unit" => '公斤',
				"now_stock" => $i * 100,
				"safe_stock" => $i * 20,
				"total_stock" => $i * 100 * 100,
				"avg_price" => $i,
				"sale_price" => $i * 2,
				"create_time" => date(config_item("log_date_format")),
			]);
		}
		
	}
}