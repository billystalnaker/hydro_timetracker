<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends LF_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->dashboard();
	}
	public function home(){
		$this->data['content']			 = $this->load->view('home', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
	public function dashboard(){
		$this->data['content']			 = $this->load->view('tpl/dashboard', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
