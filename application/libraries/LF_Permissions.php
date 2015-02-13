<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class LF_Permissions extends Permissions{

    private $is_logged = false;

    public function __construct(){
	parent::__construct();
	log_message(3, 'LF_Permissions library loaded');
    }
    public function is_logged(){
	$this->is_logged = $this->CI->session->userdata('logged');
	return $this->is_logged;
    }
    public function check_access($module_id = '', $group_id = 0){

	$module_id = ($module_id === '')?$this->CI->router->fetch_class().$this->CI->router->fetch_method():$module_id;
	//neeed multiple groups
	if($group_id === 0){
	    //if not passed get groups from session
	    $groups = $this->CI->session->userdata('group_ids');
	}elseif($group_id > 0){
	    $groups = array($group_id);
	}
	return true; //$this->CI->load->model('employee')->check_access($module_id, $groups);
    }
}
