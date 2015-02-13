<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Download extends LF_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('dompdf', 'file', 'excel'));
	}
	function per_project_pdf(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_project_report();
		$html = $this->load->view('tpl/result/per_project_results', $this->data, true);
		pdf_create($html, 'filename');
		//if you want to write it to disk and/or send it as an attachment    
	}
	function per_project_xls(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_project_report();
		excel_create($this->data['results'], 'filename');
		//if you want to write it to disk and/or send it as an attachment    
	}
}

?>