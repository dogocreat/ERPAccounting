<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instock_model extends CI_Model
{
    private $_table;
    private $_table_detail;
    public function __construct()
    {
        parent::__construct();
        $this->_table = "instock";
        $this->_table_detail = "instock_detail";
    }

    public function get_list($start, $length, $order_str, $search)
    {
        //取符合條件數量
        $this->search_condition($search);
        $count_query = $this->db->select("{$this->_table}.* , vendor.name as vendor_name")->join("vendor", "instock.vendor_id = vendor.id", 'left')->get($this->_table);
        $count = $count_query->num_rows();
        // echo $this->db->last_query();

        //取符合條件結果
        $this->search_condition($search);
        $query = $this->db->select("{$this->_table}.* , vendor.name as vendor_name")->join("vendor", "instock.vendor_id = vendor.id", 'left')->order_by($order_str)->get($this->_table, $length, $start);
        $result = $query->result();
        return [$count, $result];
    }

    /**
     * 查詢條件
     */
    public function search_condition($search)
    {
        foreach ($search as $key => $value) {
            if ($value !== '' && isset($value)) {
                if ($key === 'start_date') {
                    $this->db->where("instock_date >= ", $value);
                } else if ($key === 'end_date') {
                    $this->db->where("instock_date <= ", $value);
                } else if ($key === 'id') {
                    $this->db->where($key, $value);
                } else if ($key === 'instock_id') {
                    $this->db->where($key, $value);
                } else if ($key === 'vendor_name') {
                    $this->db->like("vendor.name", $value);
                } else {
                    $this->db->like($key, $value);
                }
            }
        }
    }

    public function add_one($data,$detail_data)
    {
        $this->db->trans_start();
        $this->load->model('Stock_model');
        $instock_id = date('YmdHis');
        $data['instock_id'] = $instock_id;
        // calu payable_price and instock_price
        $data['instock_price'] = 0;
        $data['payable_price'] = 0;
        foreach ($detail_data as $key => &$value) {
            $value['instock_id'] = $instock_id;
            $data['instock_price'] += $value['qty'] * $value['price'];
            $this->db->insert($this->_table_detail, $value);            
            $this->Stock_model->add_instock($value['stock_id'],$value['qty'],$value['price']);
        }
        $data['instock_price'] = round($data['instock_price'], 2);
        $data['payable_price'] = round($data['instock_price'] - $data['tax_price'] - $data['back_price'], 2);
        $data['unpayable_price'] = $data['payable_price'];
        $this->db->insert($this->_table, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return 0;
        }
        return 1;
    }

    public function edit_one($id, $data, $detail_data)
    {
        $this->db->trans_start();
        $this->load->model('Stock_model');
        //calu now_stock
        $instock_detail_data = $this->get_instock_detail($id);
        foreach ($instock_detail_data as $key => $value) {
            $this->Stock_model->sub_instock($value['stock_id'],$value['qty'],$value['price']);
            $this->db->delete($this->_table_detail, array('id' => $value['id']));
        }
        
        // calu payable_price and instock_price
        $data['instock_price'] = 0;
        $data['payable_price'] = 0;
        foreach ($detail_data as $key => &$value) {
            $value['instock_id'] = $id;
            $data['instock_price'] += $value['qty'] * $value['price'];
            $this->db->insert($this->_table_detail, $value);
            $this->Stock_model->add_instock($value['stock_id'],$value['qty'],$value['price']);
        }
        $data['instock_price'] = round($data['instock_price'], 2);
        $data['payable_price'] = round($data['instock_price'] - $data['tax_price'] - $data['back_price'], 2);

        //calu unpayable_price
        $instock_date = $this->get_one($id);
        $this->db->set('unpayable_price', $data['payable_price'] - $instock_date['payabled_price']);

        $this->db->update($this->_table, $data, array('instock_id' => $id));

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return 0;
        }
        return 1;
    }

    public function del_one($id)
    {
        $this->db->trans_start();
        $this->load->model('Stock_model');
        $instock_detail_data = $this->get_instock_detail($id);
        foreach ($instock_detail_data as $key => $value) {
            $this->Stock_model->sub_instock($value['stock_id'],$value['qty'],$value['price']);
            $this->db->delete($this->_table_detail, array('id' => $value['id']));
        }
        
        $this->db->delete($this->_table, array('instock_id' => $id));

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return 0;
        }
        return 1;
    }

    public function do_payabled($id,$data)
    {
        $this->db->set('unpayable_price', 'payable_price-'.$data['payabled_price'], FALSE);
        $this->db->update($this->_table, $data, array('instock_id' => $id));
        return $this->db->affected_rows();
    }

    public function get_one($id)
    {
        $query = $this->db->get_where($this->_table, ['instock_id' => $id], 1);
        return $query->row_array();
    }

    public function get_instock_detail($id){
        $query = $this->db->get_where($this->_table_detail, ['instock_id' => $id]);
        return $query->result_array();
    }

}
