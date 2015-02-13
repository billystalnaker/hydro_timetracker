<?php

if(!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Search extends LF_Controller{
    public function __construct(){
	parent::__construct();
    }
    public function _remap($method, $args = array()){
	//get search method and perform query based on that method
	//in each method check if the args if numeric(>0)
	//if it is perform search on id
	//else search through description,name,first name etc.
	//get results
	//after the switch see if the results returned more than one
	//if they did load the view for that table with the info
	//else load the edit page for that table with the info
	$cfg		 = $this->config->item('controller_table_conversion');
	$function	 = 'where';
	//just in case later on we want multiple parameters for the search
	$args		 = $args[0];
	if($args > 0){
	    $filters = [$cfg[$method]['id']=>$args];
	}else{
	    $function	 = 'or_like';
	    $filters	 = [];
	    if(is_array($cfg[$method]['search_fields'])){
		foreach($cfg[$method]['search_fields'] as $field){
		    $filters[$field] = $args;
		}
	    }
	}
	if(is_array($cfg[$method]['view_vars'])){
	    $this->load->model('modules');
	    if(isset($cfg[$method]['view_vars']['select']) && is_array($cfg[$method]['view_vars']['select'])){
		foreach($cfg[$method]['view_vars']['select'] as $select){
		    $result = $this->modules->make_select($method, $select['table']);
		    if($result){
			//yay
		    }else{
			//uh ohhh
		    }
		}
	    }
	}
	$this->db->$function($filters);
	if(isset($cfg[$method]['join']) && is_array($cfg[$method]['join'])){
	    foreach($cfg[$method]['join'] as $join){
		$this->db->join($join['table'], $join['cond'], $join['how']);
	    }
	}
	$result = $this->db->get($method);
	if($result->num_rows() > 0 && isset($cfg[$method] ['controller'])){
	    if($result->num_rows() > 1){
		$this->data [$cfg[$method]['controller']]	 = $result->result_array();
		$this->data['content']				 = $this->load->view('module/'.rtrim($cfg[$method]['controller'], 's').'/view', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	    }else{
		$this->data[rtrim($cfg[$method]['controller'], 's')]	 = array_shift($result->result_array());
		$this->data['content']					 = $this->load->view('module/'.rtrim($cfg[$method]['controller'], 's').'/edit', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	    }
	}else{
	    show_error('No data found...');
	}

//		$result
    }
}
