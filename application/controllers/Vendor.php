<?php defined('BASEPATH') or exit('No direct script access allowed');

class Vendor extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'dataTableListUrl' => '/ERPAccounting/vendor/getList',
            'openDialogUrl' => '/ERPAccounting/vendor/openDialog',
            'saveDialogUrl' => '/ERPAccounting/vendor/saveDialog',
            'delUrl' => '/ERPAccounting/vendor/delOne',
        ];
        // $this->mockadd();
        $this->layout->view('vendorView', $data);
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
            "company_address" => $this->input->post('company_address'),
            "company_phone" => $this->input->post('company_phone'),
            "company_no" => $this->input->post('company_no'),
            "company_email" => $this->input->post('company_email'),
            "phone" => $this->input->post('phone'),
            "start_time" => $this->input->post('start_time'),
            "end_time" => $this->input->post('end_time'),
		];
        $this->load->model('Vendor_model');
        list($count, $list) = $this->Vendor_model->get_list($start, $length, $order_str, $search);
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
            $this->load->model('Vendor_model');
            $data['edit_data'] = $this->Vendor_model->get_one($id);
        }

        $this->load->view('dialog/vendorDialogView', $data);
    }

    public function saveDialog()
    {
        $this->load->model('Vendor_model');
        $id = $this->input->post("id");
        if (empty($id)) {
            $affected_rows = $this->Vendor_model->add_one([
                "name" => $this->input->post('name'),
                "company_address" => $this->input->post('company_address'),
                "company_phone" => $this->input->post('company_phone'),
                "company_no" => $this->input->post('company_no'),
                "company_email" => $this->input->post('company_email'),
                "phone" => $this->input->post('phone'),
                "create_time" => date(config_item("log_date_format")),
            ]);
        } else {
            $affected_rows = $this->Vendor_model->edit_one($id, [
                "name" => $this->input->post('name'),
                "company_address" => $this->input->post('company_address'),
                "company_phone" => $this->input->post('company_phone'),
                "company_no" => $this->input->post('company_no'),
                "company_email" => $this->input->post('company_email'),
                "phone" => $this->input->post('phone'),
            ]);
        }
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
    }

    public function delOne()
    {
        $this->load->model('Vendor_model');
        $id = $this->input->post("id");
        $affected_rows = $this->Vendor_model->del_one($id);
        echo json_encode(['code' => $affected_rows > 0 ? 1 : 0]);
        return;
    }

    public function mockadd()
    {
        $this->load->model('Vendor_model');
        for ($i = 0; $i < 10000; $i++) {
            $this->Vendor_model->add_one([
                "name" => '海產公司' . $i,
                "company_address" => '407台中市西屯區台灣大道三段556巷1弄14號' . $i,
                "company_phone" => '3210235' . $i,
                "company_no" => $i . $i . $i . $i . $i . $i . $i . $i . $i . $i . $i,
                "company_email" => 'test' . $i . '@gmail.com',
                "phone" => $i . $i . $i . $i . $i . $i . $i . $i . $i . $i . $i,
                "create_time" => date(config_item("log_date_format")),
            ]);
        }

    }
}
