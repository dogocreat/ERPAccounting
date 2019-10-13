<?php defined('BASEPATH') or exit('No direct script access allowed');

class Outstock extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'dataTableListUrl' => '/ERPAccounting/outstock/getList',
            'openDialogUrl' => '/ERPAccounting/outstock/openDialog',
            'saveDialogUrl' => '/ERPAccounting/outstock/saveDialog',
			'delUrl' => '/ERPAccounting/outstock/delOne',
			'doReceivabledUrl' => '/ERPAccounting/outstock/doReceivabled',
        ];
        // $this->mockadd();
        $this->layout->view('outstockView', $data);
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
            "outstock_id" => $this->input->post('outstock_id'),
            "customer_name" => $this->input->post('customer_name'),
            "start_date" => $this->input->post('start_date'),
            "end_date" => $this->input->post('end_date'),
        ];
        $this->load->model('Outstock_model');
        list($count, $list) = $this->Outstock_model->get_list($start, $length, $order_str, $search);
        $data = [
            'data' => $list,
            'count' => $count,
        ];
        echo json_encode($data);
        return;
    }

    public function openDialog()
    {
        $id = $this->input->get("id");
        $data = [];
        if (!empty($id)) {
            $this->load->model('Outstock_model');
			$data['edit_data'] = $this->Outstock_model->get_one($id);
			$data['edit_data_detail'] = $this->Outstock_model->get_outstock_detail($id);
        }
        $this->load->model('Stock_model');
        $data['stock_items'] = $this->Stock_model->get_stock_items();
        $this->load->model('Customer_model');
        $data['customer_items'] = $this->Customer_model->get_customer_items();

        $this->load->view('dialog/outstockDialogView', $data);
    }

    public function saveDialog()
    {
        $this->load->model('Outstock_model');
		$id = $this->input->post("id");
		$details = $this->input->post('details');
        if (empty($id)) {
            $affected_rows = $this->Outstock_model->add_one([
                "outstock_date" => $this->input->post('outstock_date'),
                "customer_id" => $this->input->post('customer_id'),
                "tax_price" => $this->input->post('tax_price'),
                "back_price" => $this->input->post('back_price'),
                "create_time" => date(config_item("log_date_format")),
            ], $details);
        } else {
            $affected_rows = $this->Outstock_model->edit_one($id, [
                "outstock_date" => $this->input->post('outstock_date'),
                "customer_id" => $this->input->post('customer_id'),
                "tax_price" => $this->input->post('tax_price'),
                "back_price" => $this->input->post('back_price'),
            ], $details);
        }
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
    }

    public function delOne()
    {
        $this->load->model('Outstock_model');
        $id = $this->input->post("id");
        $affected_rows = $this->Outstock_model->del_one($id);
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
	}
	
	public function doReceivabled(){
		$this->load->model('Outstock_model');
		$id = $this->input->post("id");
		$affected_rows = $this->Outstock_model->do_receivabled($id, [
			"receivabled_price" => $this->input->post('receivabled_price'),
		]);
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
	}

    //公式 = 進貨金額 - 營業稅 - 折讓 = 應付金額
    public function mockadd()
    {
        $this->load->model('Outstock_model');
        for ($i = 0; $i < 500; $i++) {
            $this->Outstock_model->add_one([
                "outstock_date" => date('Y-m-d'),
                "outstock_id" => $i . date('YmdHis'),
                "customer_id" => $i,
                "outstock_price" => $i * 3000,
                "tax_price" => $i,
                "back_price" => $i,
                "receivable_price" => $i * 3000 - $i - $i,
                "create_time" => date(config_item("log_date_format")),
            ]);
        }

    }
}