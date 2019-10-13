<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends MY_Controller
{

    public function __construct()
    {
		parent::__construct();
    }
    public function index()
    {
        $data = [
            'dataTableListUrl' => '/ERPAccounting/customer/getList',
            'openDialogUrl' => '/ERPAccounting/customer/openDialog',
            'saveDialogUrl' => '/ERPAccounting/customer/saveDialog',
            'delUrl' => '/ERPAccounting/customer/delOne',
		];
		// $this->mockadd();
        $this->layout->view('customerView', $data);
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
			"live_address" => $this->input->post('live_address'),
			"send_address" => $this->input->post('send_address'),
			"phone" => $this->input->post('phone'),
			"company_no" => $this->input->post('company_no'),
			"tick_title" => $this->input->post('tick_title'),
			"tick_address" => $this->input->post('tick_address'),
			"start_time" => $this->input->post('start_time'),
			"end_time" => $this->input->post('end_time'),
		];
		$this->load->model('Customer_model');
		list($count,$list) = $this->Customer_model->get_list($start,$length,$order_str,$search);
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
			$this->load->model('Customer_model');
			$data['edit_data'] = $this->Customer_model->get_one($id);
		}

		$this->load->view('dialog/customerDialogView', $data);
	}

	public function saveDialog(){
		$this->load->model('Customer_model');
		$id = $this->input->post("id");
		if(empty($id)){
			$affected_rows = $this->Customer_model->add_one([
				"name" => $this->input->post('name'),
				"live_address" => $this->input->post('live_address'),
				"send_address" => $this->input->post('send_address'),
				"phone" => $this->input->post('phone'),
				"company_no" => $this->input->post('company_no'),
				"tick_title" => $this->input->post('tick_title'),
				"tick_address" => $this->input->post('tick_address'),
				"create_time" => date(config_item("log_date_format")),
			]);
		}else{
			$affected_rows = $this->Customer_model->edit_one($id,[
				"name" => $this->input->post('name'),
				"live_address" => $this->input->post('live_address'),
				"send_address" => $this->input->post('send_address'),
				"phone" => $this->input->post('phone'),
				"company_no" => $this->input->post('company_no'),
				"tick_title" => $this->input->post('tick_title'),
				"tick_address" => $this->input->post('tick_address'),
			]);
		}
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
		return;
	}

	public function delOne(){
		$this->load->model('Customer_model');
		$id = $this->input->post("id");
		$affected_rows = $this->Customer_model->del_one($id);
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
		return;
	}
	
	public function mockadd(){
		$this->load->model('Customer_model');
		for ($i=0; $i < 10000; $i++) { 
			$this->Customer_model->add_one([
				"name" => '我是誰'.$i,
				"live_address" => '407台中市西屯區台灣大道三段556巷1弄14號'.$i,
				"send_address" => '407台中市西屯區台灣大道三段556巷1弄14號'.$i,
				"phone" => $i.$i.$i.$i.$i.$i.$i.$i.$i.$i.$i,
				"company_no" => $i.$i.$i.$i.$i.$i.$i.$i.$i.$i.$i,
				"tick_title" => '發票抬頭'.$i,
				"tick_address" => '407台中市西屯區台灣大道三段556巷1弄14號'.$i,
				"create_time" => date(config_item("log_date_format")),
			]);
		}
		
	}
}