<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
    private $_table;
    public function __construct()
    {
        parent::__construct();
        $this->_table = "stock";
    }

    public function get_list($start, $length, $order_str, $search)
    {
        //取符合條件數量
        $this->search_condition($search);

        $count_query = $this->db->get($this->_table);
        $count = $count_query->num_rows();
        // echo $this->db->last_query();

        //取符合條件結果
        $this->search_condition($search);
        $query = $this->db->order_by($order_str)->get($this->_table, $length, $start);
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
                if ($key === 'start_time') {
                    $this->db->where("create_time >= ", $value);
                } else if ($key === 'end_time') {
                    $this->db->where("create_time <= ", $value);
                } else if ($key === 'now_stock') {
                    $this->db->where("now_stock > ", $value);
                } else if ($key === 'safe_stock') {
                    $this->db->where("safe_stock > ", $value);
                } else if ($key === 'warning_stock') {
                    $this->db->where("safe_stock > now_stock");
                } else if ($key === 'avg_price') {
                    $this->db->where("avg_price >= ", $value);
                } else if ($key === 'sale_price') {
                    $this->db->where("sale_price >= ", $value);
                } else if ($key === 'id') {
                    $this->db->where($key, $value);
                } else {
                    $this->db->like($key, $value);
                }
            }
        }
    }

    public function add_one($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->affected_rows();
    }

    public function edit_one($id, $data)
    {
        $this->db->update($this->_table, $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    public function del_one($id)
    {
        $this->db->delete($this->_table, array('id' => $id));
        return $this->db->affected_rows();
    }

    public function get_one($id)
    {
        $query = $this->db->get_where($this->_table, ['id' => $id], 1);
        return $query->row_array();
    }

    public function get_stock_items(){
        $query = $this->db->get($this->_table);
        $result = $query->result();
        return $result;
    }

    public function add_instock($id,$qty,$price){
        //平均成本 = (平均成本 * 總進貨數量 + 進貨數量 * 進貨單價) / (總進貨數量 + 進貨數量)
        $this->db->set('avg_price', "((avg_price * total_stock) + ($qty * $price) ) / (total_stock+".$qty.")", false);
        $this->db->set('total_stock', 'total_stock+'.$qty, false);
        $this->db->set('now_stock', 'now_stock+'.$qty, false);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function sub_instock($id,$qty,$price){
        //平均成本 = (平均成本 * 總進貨數量 - 進貨數量 * 進貨單價) / (總進貨數量 - 進貨數量)
        $this->db->set('avg_price', "((avg_price * total_stock) - ($qty * $price) ) / (total_stock-".$qty.")", false);
        $this->db->set('total_stock', 'total_stock-'.$qty, false);
        $this->db->set('now_stock', 'now_stock-'.$qty, false);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function add_outstock($id,$qty,$price){
        $this->db->set('now_stock', 'now_stock-'.$qty, false);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function sub_outstock($id,$qty,$price){
        $this->db->set('now_stock', 'now_stock+'.$qty, false);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }
}