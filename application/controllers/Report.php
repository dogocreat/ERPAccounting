<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'getDataUrl' => '/ERPAccounting/report/get_data',
            'getEchartsDataUrl' => '/ERPAccounting/report/get_echarts_data',
        ];
        $this->layout->view('reportView', $data);
    }

    public function get_data()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $this->load->model('Report_model');

        list($instock_report, $outstock_report) = $this->Report_model->get_data($start_date, $end_date);
        $this->format_zero($instock_report);
        $this->format_zero($outstock_report);
        echo json_encode(
            [
                "instock_report" => $instock_report,
                "outstock_report" => $outstock_report,
            ]
        );
        return;
    }

    public function get_echarts_data()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $diff = date_diff(date_create($start_date),date_create($end_date));
        $date_arr = [];
        for ($i=0; $i <= $diff->days; $i++) { 
            $date_arr[] = date("Y-m-d", strtotime("$start_date + {$i} day"));
        }
        $this->load->model('Report_model');
        $echarts_instock = $this->Report_model->get_echarts_instock($start_date, $end_date);
        $echarts_outstock = $this->Report_model->get_echarts_outstock($start_date, $end_date);
        list($instock,$outstock,$profit) = $this->format_echarts($date_arr, $echarts_instock, $echarts_outstock);
        echo json_encode(
            [
                "date_arr" => $date_arr,
                "instock" => $instock,
                "outstock" => $outstock,
                "profit" => $profit,
            ]
        );
        return;
    }

    public function format_echarts($date_arr, $echarts_instock, $echarts_outstock)
    {
        $init_instock = [];
        $init_outstock = [];
        $init_profit = [];
        foreach ($date_arr as $key => $value) {
            $init_instock[$key] = round(0,2);
            $init_outstock[$key] = round(0,2);
            $init_profit[$key] = round(0,2);
        }
        foreach ($date_arr as $key => $value) {
            foreach ($echarts_instock as $ikey => $ivalue) {
                if($ivalue['instock_date'] === $value){
                    $init_instock[$key] = round($ivalue['sum_instock_payable_price'],2);
                }
            }
            foreach ($echarts_outstock as $okey => $ovalue) {
                if($ovalue['outstock_date'] === $value){
                    $init_outstock[$key] = round($ovalue['sum_outstock_receivable_price'],2);
                }
            }
        }

        foreach ($init_profit as $key => &$value) {
            $value = round($init_outstock[$key] - $init_instock[$key],2);
        }
        unset($value);

        return [$init_instock,$init_outstock,$init_profit];
    }

    public function format_zero(&$instock_report)
    {
        foreach ($instock_report as $key => &$value) {
            if (empty($value)) {
                $value = sprintf ("%0.2f", $value);
            }
        }
        unset($value);
    }
}