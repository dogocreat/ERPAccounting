<?php defined('BASEPATH') or exit('No direct script access allowed');

class Instock extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'dataTableListUrl' => '/ERPAccounting/instock/getList',
            'openDialogUrl' => '/ERPAccounting/instock/openDialog',
            'saveDialogUrl' => '/ERPAccounting/instock/saveDialog',
			'delUrl' => '/ERPAccounting/instock/delOne',
			'doPayabledUrl' => '/ERPAccounting/instock/doPayabled',
        ];
        // $this->mockadd();
        $this->layout->view('instockView', $data);
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
            "instock_id" => $this->input->post('instock_id'),
            "vendor_name" => $this->input->post('vendor_name'),
            "start_date" => $this->input->post('start_date'),
            "end_date" => $this->input->post('end_date'),
        ];
        $this->load->model('Instock_model');
        list($count, $list) = $this->Instock_model->get_list($start, $length, $order_str, $search);
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
            $this->load->model('Instock_model');
			$data['edit_data'] = $this->Instock_model->get_one($id);
			$data['edit_data_detail'] = $this->Instock_model->get_instock_detail($id);
        }
        $this->load->model('Stock_model');
        $data['stock_items'] = $this->Stock_model->get_stock_items();
        $this->load->model('Vendor_model');
        $data['vendor_items'] = $this->Vendor_model->get_vendor_items();

        $this->load->view('dialog/instockDialogView', $data);
    }

    public function saveDialog()
    {
        $this->load->model('Instock_model');
		$id = $this->input->post("id");
		$details = $this->input->post('details');
        if (empty($id)) {
            $affected_rows = $this->Instock_model->add_one([
                "instock_date" => $this->input->post('instock_date'),
                "vendor_id" => $this->input->post('vendor_id'),
                "tax_price" => $this->input->post('tax_price'),
                "back_price" => $this->input->post('back_price'),
                "create_time" => date(config_item("log_date_format")),
            ], $details);
        } else {
            $affected_rows = $this->Instock_model->edit_one($id, [
                "instock_date" => $this->input->post('instock_date'),
                "vendor_id" => $this->input->post('vendor_id'),
                "tax_price" => $this->input->post('tax_price'),
                "back_price" => $this->input->post('back_price'),
            ], $details);
        }
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
    }

    public function delOne()
    {
        $this->load->model('Instock_model');
        $id = $this->input->post("id");
        $affected_rows = $this->Instock_model->del_one($id);
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
	}
	
	public function doPayabled(){
		$this->load->model('Instock_model');
		$id = $this->input->post("id");
		$affected_rows = $this->Instock_model->do_payabled($id, [
			"payabled_price" => $this->input->post('payabled_price'),
		]);
		echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
	}

    //公式 = 進貨金額 - 營業稅 - 折讓 = 應付金額
    public function mockadd()
    {
        $this->load->model('Instock_model');
        for ($i = 0; $i < 500; $i++) {
            $this->Instock_model->add_one([
                "instock_date" => date('Y-m-d'),
                "instock_id" => $i . date('YmdHis'),
                "vendor_id" => $i,
                "instock_price" => $i * 3000,
                "tax_price" => $i,
                "back_price" => $i,
                "payable_price" => $i * 3000 - $i - $i,
                "create_time" => date(config_item("log_date_format")),
            ]);
        }

    }
}