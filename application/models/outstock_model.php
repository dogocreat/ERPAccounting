<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outstock_model extends CI_Model
{
    private $_table;
    private $_table_detail;
    public function __construct()
    {
        parent::__construct();
        $this->_table = "outstock";
        $this->_table_detail = "outstock_detail";
    }

    public function get_list($start, $length, $order_str, $search)
    {
        //取符合條件數量
        $this->search_condition($search);
        $count_query = $this->db->select("{$this->_table}.* , customer.name as customer_name")->join("customer", "outstock.customer_id = customer.id", 'left')->get($this->_table);
        $count = $count_query->num_rows();
        // echo $this->db->last_query();

        //取符合條件結果
        $this->search_condition($search);
        $query = $this->db->select("{$this->_table}.* , customer.name as customer_name")->join("customer", "outstock.customer_id = customer.id", 'left')->order_by($order_str)->get($this->_table, $length, $start);
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
                    $this->db->where("outstock_date >= ", $value);
                } else if ($key === 'end_date') {
                    $this->db->where("outstock_date <= ", $value);
                } else if ($key === 'id') {
                    $this->db->where($key, $value);
                } else if ($key === 'outstock_id') {
                    $this->db->where($key, $value);
                } else if ($key === 'customer_name') {
                    $this->db->like("customer.name", $value);
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
        $outstock_id = date('YmdHis');
        $data['outstock_id'] = $outstock_id;
        // calu receivable_price and outstock_price
        $data['outstock_price'] = 0;
        $data['receivable_price'] = 0;
        foreach ($detail_data as $key => &$value) {
            $value['outstock_id'] = $outstock_id;
            $data['outstock_price'] += $value['qty'] * $value['price'];
            $this->db->insert($this->_table_detail, $value);            
            $this->Stock_model->add_outstock($value['stock_id'],$value['qty'],$value['price']);
        }
        $data['outstock_price'] = round($data['outstock_price'], 2);
        $data['receivable_price'] = round($data['outstock_price'] - $data['tax_price'] - $data['back_price'], 2);
        $data['unreceivable_price'] = $data['receivable_price'];
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
        $outstock_detail_data = $this->get_outstock_detail($id);
        foreach ($outstock_detail_data as $key => $value) {
            $this->Stock_model->sub_outstock($value['stock_id'],$value['qty'],$value['price']);
            $this->db->delete($this->_table_detail, array('id' => $value['id']));
        }
        
        // calu receivable_price and outstock_price
        $data['outstock_price'] = 0;
        $data['receivable_price'] = 0;
        foreach ($detail_data as $key => &$value) {
            $value['outstock_id'] = $id;
            $data['outstock_price'] += $value['qty'] * $value['price'];
            $this->db->insert($this->_table_detail, $value);
            $this->Stock_model->add_outstock($value['stock_id'],$value['qty'],$value['price']);
        }
        $data['outstock_price'] = round($data['outstock_price'], 2);
        $data['receivable_price'] = round($data['outstock_price'] - $data['tax_price'] - $data['back_price'], 2);

        //calu unreceivable_price
        $outstock_date = $this->get_one($id);
        $this->db->set('unreceivable_price', $data['receivable_price'] - $outstock_date['receivabled_price']);

        $this->db->update($this->_table, $data, array('outstock_id' => $id));

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
            $this->Stock_model->sub_outstock($value['stock_id'],$value['qty'],$value['price']);
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

    public function do_receivabled($id,$data)
    {
        $this->db->set('unreceivable_price', 'receivable_price-'.$data['receivabled_price'], FALSE);
        $this->db->update($this->_table, $data, array('outstock_id' => $id));
        return $this->db->affected_rows();
    }

    public function get_one($id)
    {
        $query = $this->db->get_where($this->_table, ['outstock_id' => $id], 1);
        return $query->row_array();
    }

    public function get_outstock_detail($id){
        $query = $this->db->get_where($this->_table_detail, ['outstock_id' => $id]);
        return $query->result_array();
    }

}
