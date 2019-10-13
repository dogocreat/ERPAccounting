<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
    private $_instock_table;
    private $_outstock_table;
    public function __construct()
    {
        parent::__construct();
        $this->_instock_table = "instock";
        $this->_outstock_table = "outstock";
    }

    public function get_data($start_date, $end_date)
    {
        $instock_report = $this->get_instock_report($start_date,$end_date);
        $outstock_report = $this->get_outstock_report($start_date,$end_date);
        return [$instock_report, $outstock_report];
    }
    
    public function get_instock_report($start_date, $end_date){
        $this->db->select_sum("instock_price","sum_instock_price");
        $this->db->select_sum("tax_price","sum_instock_tax_price");
        $this->db->select_sum("back_price","sum_instock_back_price");
        $this->db->select_sum("payable_price","sum_instock_payable_price");
        $this->db->select_sum("payabled_price","sum_instock_payabled_price");
        $this->db->select_sum("unpayable_price","sum_instock_unpayable_price");
        $this->db->from($this->_instock_table);
        $this->db->where("instock_date >= ", $start_date);
        $this->db->where("instock_date <= ", $end_date);
        // $this->db->having('count(*) > 0');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row_array() : [];
    }

    public function get_outstock_report($start_date, $end_date){
        $this->db->select_sum("outstock_price","sum_outstock_price");
        $this->db->select_sum("tax_price","sum_outstock_tax_price");
        $this->db->select_sum("back_price","sum_outstock_back_price");
        $this->db->select_sum("receivable_price","sum_outstock_receivable_price");
        $this->db->select_sum("receivabled_price","sum_outstock_receivabled_price");
        $this->db->select_sum("unreceivable_price","sum_outstock_unreceivable_price");
        $this->db->from($this->_outstock_table);
        $this->db->where("outstock_date >= ", $start_date);
        $this->db->where("outstock_date <= ", $end_date);
        // $this->db->having('count(*) > 0');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row_array() : [];
    }
    
    public function get_echarts_instock($start_date, $end_date){
        $this->db->select("instock_date");
        $this->db->select_sum("payable_price","sum_instock_payable_price");
        $this->db->from($this->_instock_table);
        $this->db->where("instock_date >= ", $start_date);
        $this->db->where("instock_date <= ", $end_date);
        $this->db->group_by("instock_date");
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result_array() : [];
    }

    public function get_echarts_outstock($start_date, $end_date){
        $this->db->select("outstock_date");
        $this->db->select_sum("receivable_price","sum_outstock_receivable_price");
        $this->db->from($this->_outstock_table);
        $this->db->where("outstock_date >= ", $start_date);
        $this->db->where("outstock_date <= ", $end_date);
        $this->db->group_by("outstock_date");
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result_array() : [];
    }

    public function get_data_by_date(){
        $sql=<<<EOT
        select 
        instock_date,
        sum(instock_price) as sum_instock_price,
        sum(tax_price) as sum_instock_tax_price,
        sum(back_price) as sum_instock_back_price,
        sum(payable_price) as sum_instock_payable_price,
        sum(payabled_price) as sum_instock_payabled_price,
        sum(payabled_price)  as sum_instock_payabled_price,
        sum(unpayable_price) as sum_instock_unpayable_price
        from instock
        where instock_date >= '2019-10-11' and instock_date <= "2019-10-13"
        group by instock_date"
EOT;
    }
}
