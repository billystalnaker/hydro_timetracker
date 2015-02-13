<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Module extends LF_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function users($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/users/view');
		}
		if(!$this->flexi_auth->is_privileged('Users') || !$this->flexi_auth->is_privileged(ucfirst($action).' Users')){
//set flashdata saying you dont have access to this
			if(USER_ID !== $id){
				redirect('home/dashboard');
			}
		}
		$this->load->model('modules');
		$groups				 = $this->modules->get_groups(true);
		$group_options		 = array();
		$group_options['']	 = 'Please Select...';
		foreach($groups as $group){
			$group_options[$group['ugrp_id']] = $group['ugrp_name'];
		}
		$this->data['group_options'] = $group_options;
		$this->data['message']		 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_user();
				$this->data['content']	 = $this->load->view('module/user/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_user($id);
				$this->modules->update_user_account($id);
				$this->data['content']	 = $this->load->view('module/user/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_users();
				$this->modules->update_users();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/user/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function groups($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/groups/view');
		}
		if(!$this->flexi_auth->is_privileged('Groups') || !$this->flexi_auth->is_privileged(ucfirst($action).' Groups')){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_group();
				$this->data['content']	 = $this->load->view('module/group/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_group($id);
				$this->modules->update_group($id);
				$this->data['content']	 = $this->load->view('module/group/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_groups();
				$this->modules->update_groups();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/group/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function privileges($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/privileges/view');
		}
		if(!$this->flexi_auth->is_privileged('Privileges') || !$this->flexi_auth->is_privileged(ucfirst($action).' Privileges')){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_privilege();
				$this->data['content']	 = $this->load->view('module/privilege/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_privilege($id);
				$this->modules->update_privilege($id);
				$this->data['content']	 = $this->load->view('module/privilege/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_privileges();
				$this->modules->update_privileges();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/privilege/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function user_privileges($id = NULL){
		$id = (!is_null($id))?$id:0;
		if($id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/users/view');
		}
		if(!$this->flexi_auth->is_privileged('User Privileges')){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$this->modules->update_user_privileges($id);
		$this->data['content']	 = $this->load->view('module/user_privilege/edit', $this->data, true);

		$this->load->view('tpl/structure', $this->data);
	}
	public function group_privileges($id = NULL){
		$id = (!is_null($id))?$id:0;
		if($id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/groups/view');
		}
		if(!$this->flexi_auth->is_privileged('Group Privileges')){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$this->modules->update_group_privileges($id);
		$this->data['content']	 = $this->load->view('module/group_privilege/edit', $this->data, true);

		$this->load->view('tpl/structure', $this->data);
	}
	public function reports($report){
		$priv = ucwords(str_replace('_', ' ', $report));
		if(!$this->flexi_auth->is_privileged('Reports') || !$this->flexi_auth->is_privileged($priv)){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$report = "report_".$report;
		if(!method_exists($this, $report) || !is_callable(array($this, $report))){
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->$report();
	}
	public function report_analyze_clock_entries_per_project(){
		$this->modules->analyze_clock_entries_per_project_report();
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$this->data['content']	 = $this->load->view('module/report/analyze_clock_entry_per_project_report', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
	public function report_analyze_clock_entries_per_task(){
		$this->modules->analyze_clock_entries_per_task_report();
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$this->data['content']	 = $this->load->view('module/report/analyze_clock_entry_per_task_report', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
	public function projects($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/projects/view');
		}
		if(!$this->flexi_auth->is_privileged('Projects') || !$this->flexi_auth->is_privileged(ucfirst($action).' Projects')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$external_users					 = $this->modules->get_external_users(true);
		$external_user_eng_options		 = array();
		$external_user_eng_options['']	 = 'Please Select...';
		$external_user_sale_options		 = array();
		$external_user_sale_options['']	 = 'Please Select...';
		if(is_array($external_users)){
			foreach($external_users as $user){
				switch($user['external_user_type_id']){
					case 1:
						$external_user_eng_options[$user['id']]	 = $user['first_name'].' '.$user['last_name'];
						break;
					case 2:
						$external_user_sale_options[$user['id']] = $user['first_name'].' '.$user['last_name'];
						break;
					default:
						break;
				}
			}
		}
		$this->data['external_user_sale_options']	 = $external_user_sale_options;
		$this->data['external_user_eng_options']	 = $external_user_eng_options;


		$customers				 = $this->modules->get_customers(true);
		$customer_options		 = array();
		$customer_options['']	 = 'Please Select...';
		foreach($customers as $customer){
			$customer_options[$customer['id']] = ($customer['company'] != '')?$customer['company']:($customer['first_name'].' '.$customer['last_name']);
		}
		$this->data['customer_options'] = $customer_options;

		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_project();
				$this->data['content']	 = $this->load->view('module/project/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_project($id);
				$this->modules->update_project($id);
				$this->data['content']	 = $this->load->view('module/project/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_projects();
				$this->modules->update_projects();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/project/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function tasks($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/tasks/view');
		}
		if(!$this->flexi_auth->is_privileged('Tasks') || !$this->flexi_auth->is_privileged(ucfirst($action).' Tasks')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_task();
				$this->data['content']	 = $this->load->view('module/task/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_task($id);
				$this->modules->update_task($id);
				$this->data['content']	 = $this->load->view('module/task/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_tasks();
				$this->modules->update_tasks();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/task/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function project_tasks($id = NULL){
		$id = (!is_null($id))?$id:0;
		if($id <= 0){
//set flashdata sayign you must have id in order to edit
			redirect('module/projects/view');
		}
		if(!$this->flexi_auth->is_privileged('Project Tasks')){
//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$this->modules->update_project_tasks($id);
		$this->data['content']	 = $this->load->view('module/project_task/edit', $this->data, true);

		$this->load->view('tpl/structure', $this->data);
	}
	public function clock_entries($action = NULL, $id = NULL, $project_id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/clock_entries/view');
		}
		if(!$this->flexi_auth->is_privileged('Clock Entries') || !$this->flexi_auth->is_privileged(ucfirst($action).' Clock Entries')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$users				 = $this->modules->get_users(true);
		$user_options		 = array();
		$user_options['']	 = 'Please Select...';
		foreach($users as $user){
			$user_options[$user['uacc_id']] = $user['upro_first_name'].' '.$user['upro_last_name'];
		}
		$this->data['user_options'] = $user_options;

		$projects			 = $this->modules->get_projects(true);
		$project_options	 = array();
		$project_options[''] = 'Please Select...';
		foreach($projects as $project){
			$project_options[$project['id']] = $project['name'];
		}
		$this->data['project_options']	 = $project_options;
		$this->data['message']			 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_clock_entry();
				$this->data['content']	 = $this->load->view('module/clock_entry/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_clock_entry($id);
				$this->modules->update_clock_entry($id);
				$this->data['content']	 = $this->load->view('module/clock_entry/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_clock_entries();
				$this->modules->update_clock_entries();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/clock_entry/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function customers($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/customers/view');
		}
		if(!$this->flexi_auth->is_privileged('Customers') || !$this->flexi_auth->is_privileged(ucfirst($action).' Customers')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_customer();
				$this->data['content']	 = $this->load->view('module/customer/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_customer($id);
				$this->modules->update_customer($id);
				$this->data['content']	 = $this->load->view('module/customer/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_customers();
				$this->modules->update_customers();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/customer/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function external_users($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/external_users/view');
		}
		if(!$this->flexi_auth->is_privileged('External Users') || !$this->flexi_auth->is_privileged(ucfirst($action).' External Users')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$external_user_types			 = $this->modules->get_external_user_types(true);
		$external_user_type_options		 = array();
		$external_user_type_options['']	 = 'Please Select...';
		foreach($external_user_types as $external_user_type){
			$external_user_type_options[$external_user_type['id']] = $external_user_type['name'];
		}
		$this->data['external_user_type_options'] = $external_user_type_options;

		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_external_user();
				$this->data['content']	 = $this->load->view('module/external_user/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_external_user($id);
				$this->modules->update_external_user($id);
				$this->data['content']	 = $this->load->view('module/external_user/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_external_users();
				$this->modules->update_external_users();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/external_user/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function external_user_types($action = NULL, $id = NULL){
		$action	 = (!is_null($action))?$action:'view';
		$id		 = (!is_null($id))?$id:0;
		if($action === 'edit' && $id <= 0){
			//set flashdata sayign you must have id in order to edit
			redirect('module/external_user_types/view');
		}
		if(!$this->flexi_auth->is_privileged('External User Types') || !$this->flexi_auth->is_privileged(ucfirst($action).' External User Types')){
			//set flashdata saying you dont have access to this
			redirect('home/dashboard');
		}
		$this->load->model('modules');
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		switch($action){
			case 'add':
				$this->modules->insert_external_user_type();
				$this->data['content']	 = $this->load->view('module/external_user_type/add', $this->data, true);
				break;
			case 'edit':
				$this->modules->get_external_user_type($id);
				$this->modules->update_external_user_type($id);
				$this->data['content']	 = $this->load->view('module/external_user_type/edit', $this->data, true);
				break;
			case 'view':
			default:
				$this->modules->get_external_user_types();
				$this->modules->update_external_user_types();
// Set any returned status/error messages.

				$this->data['content'] = $this->load->view('module/external_user_type/view', $this->data, true);
				break;
		}
		$this->load->view('tpl/structure', $this->data);
	}
	public function valid_date($input){
		$valid_date	 = strtotime('2000-01-01');
		$t_stamp	 = strtotime($input);
		if($t_stamp > $valid_date){
			return true;
		}
		$this->form_validation->set_message('valid_date', 'The %s field must be a valid date.');
		return false;
	}
}
