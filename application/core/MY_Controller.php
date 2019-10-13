<?php
/*
*
* --------------------------------------------------------------------
*  控制器超类，加入pjax请求判断
* --------------------------------------------------------------------
*
*  @authors ekin.cen <weibo.com/2839892994>
*  
*/
class MY_Controller extends CI_Controller{
 
        protected $_layout='layout_default';
 
        public function __construct(){
                parent::__construct();
                $this->_initialize();
        }
 
        protected function _initialize(){
                $this->_set_layout();
                // check if is pjax request
               if (array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX'])
               {
                   $this->layout->disable_layout = TRUE;
               }
        }
 
        protected function _set_layout(){
        $this->load->library('layout');
        //set template
        $this->layout->set_layout($this->_layout);
        }

        /**
         * 共用方法 DataTable 排序串接使用
         */
        protected function searchOrderBy($colums, $order){
		$order_str_arr = [];
		if(isset($order) && !empty($order)){
			foreach ($order as $key => $value) {
				$order_str_arr[] = $colums[$value['column']]['data'] . " " . $value['dir'];
			}
		}
		return implode(',',$order_str_arr);
	}
}
/* End of file */