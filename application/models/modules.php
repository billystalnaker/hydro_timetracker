<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends LF_Model {
// The following method prevents an error occurring when $this->data is modified.
// Error Message: 'Indirect modification of overloaded property Demo_cart_admin_model::$data has no effect'.
	public function &__get($key){
		$CI = & get_instance();
		return $CI->$key;
	}
###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
// User Accounts
###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	/**
	 * get_user_accounts
	 * Gets a paginated list of users that can be filtered via the user search form, filtering by the users email and first and last names.
	 */
	function get_users($return = false){
// Select user data to be displayed.
		$sql_select	 = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'username'),
			$this->flexi_auth->db_column('user_group', 'name'),
			'upro_first_name',
			'upro_last_name',
		);
		$sql_where	 = false;
		if(!$this->flexi_auth->is_admin()){
			$sql_where = [$this->flexi_auth->db_column('user_acc', 'id')." !=" => $this->auth->auth_settings['admin_user']];
		}
		$this->flexi_auth->sql_select($sql_select);
		$ret = $this->flexi_auth->get_user_array(FALSE, $sql_where);
		if($return){
			return $ret;
		}
		$this->data['users'] = $ret;
	}
	function get_user($user_id, $return = false){
		$filters[$this->flexi_auth->db_column('user_acc', 'id')] = $user_id;

		$ret = array_shift($this->flexi_auth->get_users_query(FALSE, $filters)->result_array());
		if($return){
			return $ret;
		}
		$this->data['user'] = $ret;
	}
	/**
	 * update_user_account
	 * Updates the account and profile data of a specific user.
	 * Note: The user profile table ('demo_user_profiles') is used in this demo as an example of relating additional user data to the auth libraries account tables.
	 */
	function update_user_account($user_id){
		$this->load->library('form_validation');

// Set validation rules.

		$validation_rules = array(
			array(
				'field'	 => 'update_user_first_name',
				'label'	 => 'First Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'update_user_last_name',
				'label'	 => 'Last Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'update_user_email',
				'label'	 => 'Email Address',
				'rules'	 => 'required|valid_email'),
		);
		if($this->flexi_auth->is_privileged('Edit Groups')){
			$validation_rules[] = array(
				'field'	 => 'update_user_group_id',
				'label'	 => 'User Group',
				'rules'	 => 'required|integer');
		}

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// 'Update User Account' form data is valid.
// IMPORTANT NOTE: As we are updating multiple tables (The main user account and user profile tables), it is very important to pass the
// primary key column and value in the $profile_data for any custom user tables being updated, otherwise, the function will not
// be able to identify the correct custom data row.
// In this example, the primary key column and value is 'upro_id' => $user_id.
			$profile_data = array(
				'upro_first_name'									 => $this->input->post('update_user_first_name'),
				'upro_last_name'									 => $this->input->post('update_user_last_name'),
				$this->flexi_auth->db_column('user_acc', 'email')	 => $this->input->post('update_user_email'),
			);
			if($this->flexi_auth->is_privileged('Edit Groups')){
				$profile_data[$this->flexi_auth->db_column('user_acc', 'group_id')] = $this->input->post('update_user_group_id');
			}


// If we were only updating profile data (i.e. no email, username or group included), we could use the 'update_custom_user_data()' function instead.
			$this->flexi_auth->update_user($user_id, $profile_data);

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/users/view');
		}

		return FALSE;
	}
	/**
	 * delete_users
	 * Delete all user accounts that have not been activated X days since they were registered.
	 */
	function delete_users($inactive_days){
// Deleted accounts that have never been activated.
		$this->flexi_auth->delete_unactivated_users($inactive_days);

// Save any public or admin status or error messages to CI's flash session data.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
		redirect('auth_admin/manage_user_accounts');
	}
	/**
	 * update_user_accounts
	 * The function loops through all POST data checking the 'Suspend' and 'Delete' checkboxes that have been checked, and updates/deletes the user accounts accordingly.
	 */
	function update_users(){
// If user has privileges, delete users.
		if($this->input->post()){
			if($this->flexi_auth->is_privileged('Delete Users')){
				if($delete_users = $this->input->post('delete_user')){
					foreach($delete_users as $user_id => $delete){
// Note: As the 'delete_user' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
						$this->flexi_auth->delete_user($user_id);
					}
				}
			}

// Update User Suspension Status.
// Suspending a user prevents them from logging into their account.
			if($user_status = $this->input->post('suspend_status')){
// Get current statuses to check if submitted status has changed.
				$current_status = $this->input->post('current_status');

				foreach($user_status as $user_id => $status){
					if($current_status[$user_id] != $status){
						if($status == 1){
							$this->flexi_auth->update_user($user_id, array(
								$this->flexi_auth->db_column('user_acc', 'suspend') => 1));
						}else{
							$this->flexi_auth->update_user($user_id, array(
								$this->flexi_auth->db_column('user_acc', 'suspend') => 0));
						}
					}
				}
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/users/view');
		}
	}
	/**
	 * insert_user
	 * Inserts a new user .
	 */
	function insert_user(){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_user_user_name',
				'label'	 => 'User Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'insert_user_user_name',
				'label'	 => 'User Name',
				'rules'	 => 'is_unique[user_accounts.uacc_username]'),
			array(
				'field'	 => 'insert_user_first_name',
				'label'	 => 'First Name',
				'rules'	 => ''),
			array(
				'field'	 => 'insert_user_last_name',
				'label'	 => 'Last Name',
				'rules'	 => ''),
			array(
				'field'	 => 'insert_user_email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email'),
			array(
				'field'	 => 'insert_user_group_id',
				'label'	 => 'Group ID',
				'rules'	 => 'integer|required'),
			array(
				'field'	 => 'insert_user_password',
				'label'	 => 'Password',
				'rules'	 => 'required'),
			array(
				'field'	 => 'insert_user_password_confirmation',
				'label'	 => 'Password Confirmation',
				'rules'	 => 'matches[insert_user_password]')
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get user group data from input.
			$user_name		 = $this->input->post('insert_user_user_name');
			$user_email		 = $this->input->post('insert_user_email');
			$password		 = $this->input->post('insert_user_password');
			$user_first_name = $this->input->post('insert_user_first_name');
			$user_last_name	 = $this->input->post('insert_user_last_name');
			$user_group_id	 = $this->input->post('insert_user_group_id');
			$user_data		 = array(
				'upro_first_name'	 => $user_first_name,
				'upro_last_name'	 => $user_last_name,
			);
			if($this->flexi_auth->insert_user($user_email, $user_name, $password, $user_data, $user_group_id, true)){
// Redirect user.
				redirect('module/users/view');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		}
	}
	function get_external_users($return = false){
		$ret = $this->db->get('external_user')->result_array();
		if($return){
			return $ret;
		}
		$this->data['external_users'] = $ret;
	}
	function get_external_user($external_user_id, $return = false){
		$filters = array('id' => $external_user_id);
		$ret	 = array_shift($this->db->where($filters)->get('external_user')->result_array());
		if($return){
			return $ret;
		}
		$this->data['project'] = $ret;
	}
	function update_external_user($external_user_id, $return = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_external_user_first_name',
				'label'	 => 'External User First Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'update_external_user_last_name',
				'label'	 => 'External User Last Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'update_external_user_type_id',
				'label'	 => 'External User Type',
				'rules'	 => 'required|int'),
			array(
				'field'	 => 'update_external_user_email',
				'label'	 => 'External User Email',
				'rules'	 => 'trim|required|valid_email'),
		);
		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data							 = array();
			$data['first_name']				 = $this->input->post('update_external_user_first_name');
			$data['last_name']				 = $this->input->post('update_external_user_last_name');
			$data['external_user_type_id']	 = $this->input->post('update_external_user_type_id');
			$data['email']					 = $this->input->post('update_external_user_email');



			$sql_where = array('id' => $external_user_id);
			$this->db->update('external_user', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$return){
// Redirect user.
				redirect('module/external_users/view');
			}
		}
	}
	function update_external_users(){
// Delete external_users.
		if($this->flexi_auth->is_privileged('Delete External Users')){
			if($delete_external_users = $this->input->post('delete_external_user')){
				foreach($delete_external_users as $external_user_id => $delete){
					// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
					// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $external_user_id);
					// Delete privileges.
					$this->db->delete('external_user', $sql_where);
				}
				// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

				// Redirect user.
				redirect('module/external_users/view');
			}
		}
	}
	function insert_external_user($return = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_external_user_first_name',
				'label'	 => 'External User First Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'insert_external_user_last_name',
				'label'	 => 'External User Last Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'insert_external_user_type_id',
				'label'	 => 'External User Type',
				'rules'	 => 'required|int'),
			array(
				'field'	 => 'insert_external_user_email',
				'label'	 => 'External User Email',
				'rules'	 => 'trim|required|valid_email'),
		);
		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data							 = array();
			$data['first_name']				 = $this->input->post('insert_external_user_first_name');
			$data['last_name']				 = $this->input->post('insert_external_user_last_name');
			$data['external_user_type_id']	 = $this->input->post('insert_external_user_type_id');
			$data['email']					 = $this->input->post('insert_external_user_email');

			$this->db->insert('external_user', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($return){
				return $ret;
			}
			redirect('module/external_users/view');
		}
	}
	function get_external_user_types($return = false){
		$ret = $this->db->get('external_user_type')->result_array();
		if($return){
			return $ret;
		}
		$this->data['external_user_types'] = $ret;
	}
	function get_external_user_type($external_user_type_id, $return = false){
		$filters = array('id' => $external_user_type_id);
		$ret	 = array_shift($this->db->where($filters)->get('external_user_type')->result_array());
		if($return){
			return $ret;
		}
		$this->data['project'] = $ret;
	}
	function update_external_user_type($external_user_type_id, $return = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_external_user_type_name',
				'label'	 => 'External User Type Name',
				'rules'	 => 'required'),
		);
		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data			 = array();
			$data['name']	 = $this->input->post('update_external_user_type_name');



			$sql_where = array('id' => $external_user_type_id);
			$this->db->update('external_user_type', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$return){
// Redirect user.
				redirect('module/external_user_types/view');
			}
		}
	}
	function update_external_user_types(){
// Delete external_user_types.
		if($this->flexi_auth->is_privileged('Delete external_user_types')){
			if($delete_external_user_types = $this->input->post('delete_external_user_type')){
				foreach($delete_external_user_types as $external_user_type_id => $delete){
					// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
					// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $external_user_type_id);
					// Delete privileges.
					$this->db->delete('external_user_type', $sql_where);
				}
				// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

				// Redirect user.
				redirect('module/external_user_types/view');
			}
		}
	}
	function insert_external_user_type($return = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_external_user_type_name',
				'label'	 => 'External User Type Name',
				'rules'	 => 'required'),
		);
		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data			 = array();
			$data['name']	 = $this->input->post('insert_external_user_type_name');
			$this->db->insert('external_user_type', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($return){
				return $ret;
			}
			redirect('module/external_user_types/view');
		}
	}
	function get_groups($return = false){
// Select user data to be displayed.
		$sql_select	 = array(
			$this->flexi_auth->db_column('user_group', 'id'),
			$this->flexi_auth->db_column('user_group', 'name'),
			$this->flexi_auth->db_column('user_group', 'description'),
		);
		$sql_where	 = false;
		if(!$this->flexi_auth->is_admin()){
			$sql_where = [$this->flexi_auth->db_column('user_group', 'id')." !=" => $this->auth->auth_settings['admin_group']];
		}
		$this->flexi_auth->sql_select($sql_select);
		$groups = $this->flexi_auth->get_user_group_array(FALSE, $sql_where);
		if($return){
			return $groups;
		}
		$this->data['groups'] = $groups;
	}
	function get_privileges($return = false){
// Select user data to be displayed.
		$sql_select = array(
			$this->flexi_auth->db_column('user_privilege', 'id'),
			$this->flexi_auth->db_column('user_privilege', 'name'),
			$this->flexi_auth->db_column('user_privilege', 'description'),
		);
		if(!$this->flexi_auth->is_admin()){
			$this->flexi_auth->sql_where('upriv_id !=', 5);
			$this->flexi_auth->sql_where('upriv_id !=', 6);
			$this->flexi_auth->sql_where('upriv_id !=', 8);
			$this->flexi_auth->sql_where('upriv_id !=', 12);
			$this->flexi_auth->sql_where('upriv_id !=', 17);
		}
		$this->flexi_auth->sql_select($sql_select);
		$privileges = $this->flexi_auth->get_privilege_array();
		if($return){
			return $privileges;
		}
		$this->data['privileges'] = $privileges;
	}
	function get_projects($return = false){
// Select user data to be displayed.
		$sql_select = array('id', 'name', 'description', 'quote_number', 'quote_date', 'customer_id', 'engineer', 'specialist', 'quote_amount');
		if($return){
			return $this->db->select($sql_select)->get('project')->result_array();
		}
		$this->data['projects'] = $this->db->select($sql_select)->get('project')->result_array();
	}
	function get_clock_entries($return = false){
// Select user data to be displayed.
		$sql_select	 = array('id', 'project_task_id', 'user_id', 'start', 'stop');
		$ret		 = $this->db->select($sql_select)->get('clock_entry')->result_array();
		if($return){
			return $ret;
		}
		$this->data['clock_entries'] = $ret;
	}
	function get_tasks($return = false){
// Select user data to be displayed.
		$sql_select = array('id', 'name', 'description');
		if($return){
			return $this->db->select($sql_select)->get('task')->result_array();
		}
		$this->data['tasks'] = $this->db->select($sql_select)->get('task')->result_array();
	}
	function get_customers($return = false){
// Select user data to be displayed.
		$sql_select = array('id', 'company', 'first_name', 'last_name');
		if($return){
			return $this->db->select($sql_select)->get('customer')->result_array();
		}
		$this->data['customers'] = $this->db->select($sql_select)->get('customer')->result_array();
	}
	function get_defects($return = false){
// Select user data to be displayed.
		$sql_select	 = array('id', 'name', 'description', 'defect_type_id', 'active');
		$defects	 = $this->db->select($sql_select)->get('defect')->result_array();
		if($return){
			return $defects;
		}
		$this->data['defects'] = $defects;
	}
	function get_project_tasks($project_id, $return = false){
// Select user data to be displayed.
		$sql_select		 = array('id', 'project_id', 'task_id');
		$sql_where		 = array(
			'project_id' => $project_id
		);
		$project_tasks	 = $this->db->select($sql_select)->where($sql_where)->get('project_task')->result_array();
		if($return){
			return $project_tasks;
		}
		$this->data['project_tasks'] = $project_tasks;
	}
	function get_group($group_id){
		$filters			 = array(
			$this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = array_shift($this->flexi_auth->get_user_group_array(FALSE, $filters));
	}
	function get_privilege($privilege_id){
		$filters				 = array(
			$this->flexi_auth->db_column('user_privileges', 'id') => $privilege_id);
		$this->data['privilege'] = array_shift($this->flexi_auth->get_privilege_array(FALSE, $filters));
	}
	function get_project($project_id, $return = false){
		$filters = array('id' => $project_id);
		$ret	 = array_shift($this->db->where($filters)->get('project')->result_array());
		if($return){
			return $ret;
		}
		$this->data['project'] = $ret;
	}
	function get_clock_entry($clock_entry_id){
		$filters					 = array('id' => $clock_entry_id);
		$this->data['clock_entry']	 = array_shift($this->db->where($filters)->get('clock_entry')->result_array());
	}
	function get_project_task($project_task_id, $return = false){
		$filters = array('id' => $project_task_id);
		$ret	 = array_shift($this->db->where($filters)->get('project_task')->result_array());
		if($return){
			return $ret;
		}
		$this->data['project_task'] = $ret;
	}
	function get_task($task_id, $return = false){
		$filters = array('id' => $task_id);
		$ret	 = array_shift($this->db->where($filters)->get('task')->result_array());
		if($return){
			return $ret;
		}
		$this->data['task'] = $ret;
	}
	function get_customer($customer_id, $return = false){
		$filters = array('id' => $customer_id);
		$ret	 = array_shift($this->db->where($filters)->get('customer')->result_array());
		if($return){
			return $ret;
		}
		$this->data['customer'] = $ret;
	}
	/**
	 * update_user_group
	 * Updates a specific user group.
	 */
	function update_group($group_id){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_group_name',
				'label'	 => 'Group Name',
				'rules'	 => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get user group data from input.
			$data = array(
				$this->flexi_auth->db_column('user_group', 'name')			 => $this->input->post('update_group_name'),
				$this->flexi_auth->db_column('user_group', 'description')	 => $this->input->post('update_group_desc'),
			);

			$this->flexi_auth->update_group($group_id, $data);

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/groups/view');
		}
	}
	/**
	 * update_privilege
	 * Updates a specific privilege.
	 */
	function update_privilege($privilege_id){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_privilege_name',
				'label'	 => 'Privilege Name',
				'rules'	 => 'required')
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get privilege data from input.
			$data = array(
				$this->flexi_auth->db_column('user_privileges', 'name')			 => $this->input->post('update_privilege_name'),
				$this->flexi_auth->db_column('user_privileges', 'description')	 => $this->input->post('update_privilege_desc')
			);

			$this->flexi_auth->update_privilege($privilege_id, $data);

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/privileges/view');
		}
	}
	function update_project($project_id, $ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_project_name',
				'label'	 => 'Project Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'update_project_quote_amount',
				'label'	 => 'Quote Amount',
				'rules'	 => 'greater_than[0]'),
			array(
				'field'	 => 'update_project_quote_date',
				'label'	 => 'Quote Date',
				'rules'	 => 'callback_valid_date'),
			array(
				'field'	 => 'update_project_desc',
				'label'	 => 'Project Description',
				'rules'	 => ''),
			array(
				'field'	 => 'update_project_quote_number',
				'label'	 => 'Quote Number',
				'rules'	 => 'int'),
			array(
				'field'	 => 'update_project_customer_id',
				'label'	 => 'Customer',
				'rules'	 => 'int'),
			array(
				'field'	 => 'update_project_engineer',
				'label'	 => 'Engineer',
				'rules'	 => 'int'),
			array(
				'field'	 => 'update_project_specialist',
				'label'	 => 'Specialist',
				'rules'	 => 'int'),
		);
		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data					 = array();
			$data['name']			 = $this->input->post('update_project_name');
			$data['description']	 = $this->input->post('update_project_desc');
			$data['quote_date']		 = date('Y-m-d H:i:s', strtotime($this->input->post('update_project_quote_date')));
			$data['quote_number']	 = $this->input->post('update_project_quote_number');
			$data['customer_id']	 = $this->input->post('update_project_customer_id');
			$data['engineer']		 = $this->input->post('update_project_engineer');
			$data['specialist']		 = $this->input->post('update_project_specialist');
			$data['quote_amount']	 = $this->input->post('update_project_quote_amount');



			$sql_where = array('id' => $project_id);
			$this->db->update('project', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$ajax){
// Redirect user.
				redirect('module/projects/view');
			}
		}
	}
	function update_clock_entry($clock_entry_id, $ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
//			array(
//				'field'	 => 'update_clock_entry_quote_amount',
//				'label'	 => 'Project Task ID',
//				'rules'	 => 'int'),
			array(
				'field'	 => 'update_clock_entry_start',
				'label'	 => 'Start Time',
				'rules'	 => 'callback_valid_date'),
			array(
				'field'	 => 'update_clock_entry_stop',
				'label'	 => 'Stop Time',
				'rules'	 => 'callback_valid_date'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data			 = array();
			$data['user_id'] = $this->input->post('update_clock_entry_user_id');
			$data['start']	 = date('Y-m-d H:i:s', strtotime($this->input->post('update_clock_entry_start')));
			$data['stop']	 = date('Y-m-d H:i:s', strtotime($this->input->post('update_clock_entry_stop')));



			$sql_where = array('id' => $clock_entry_id);
			$this->db->update('clock_entry', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$ajax){
// Redirect user.
				redirect('module/clock_entries/view');
			}
		}
	}
	function update_task($task_id, $ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_task_name',
				'label'	 => 'Task Name',
				'rules'	 => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data				 = array();
			$data['name']		 = $this->input->post('update_task_name');
			$data['description'] = $this->input->post('update_task_desc');
			$sql_where			 = array('id' => $task_id);
			$this->db->update('task', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$ajax){
// Redirect user.
				redirect('module/tasks/view');
			}
		}
	}
	function update_customer($customer_id, $ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'update_customer_company',
				'label'	 => 'Company Name',
				'rules'	 => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get st_light data from input.
			$data				 = array();
			$data['company']	 = $this->input->post('update_customer_company');
			$data['first_name']	 = $this->input->post('update_customer_first_name');
			$data['last_name']	 = $this->input->post('update_customer_last_name');

			$sql_where = array('id' => $customer_id);
			$this->db->update('customer', $data, $sql_where);
			if($this->db->affected_rows() == 1){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			if(!$ajax){
// Redirect user.
				redirect('module/customers/view');
			}
		}
	}
	/**
	 * update_user_privileges
	 * Updates the privileges for a specific user.
	 */
	function update_user_privileges($user_id){
// If 'Update User Privilege' form has been submitted, update the user privileges.
		if($this->input->post('update_user_privilege')){
// Update privileges.
			foreach($this->input->post('update') as $row){
				if($row['current_status'] != $row['new_status']){
// Insert new user privilege.
					if($row['new_status'] == 1){
						$this->flexi_auth->insert_privilege_user($user_id, $row['id']);
					}
// Delete existing user privilege.
					else{
						$sql_where = array(
							$this->flexi_auth->db_column('user_privilege_users', 'user_id')		 => $user_id,
							$this->flexi_auth->db_column('user_privilege_users', 'privilege_id') => $row['id']
						);

						$this->flexi_auth->delete_privilege_user($sql_where);
					}
				}
			}
// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect("module/user_privileges/$user_id");
		}
// Get users profile data.
		$sql_select			 = array(
			'upro_first_name',
			'upro_last_name',
			$this->flexi_auth->db_column('user_acc', 'group_id'),
			$this->flexi_auth->db_column('user_group', 'name')
		);
		$sql_where			 = array(
			$this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user']	 = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);

// Get all privilege data.
		$this->data['privileges']		 = $this->get_privileges(true);
// Get user groups current privilege data.
		$sql_select						 = array(
			$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where						 = array(
			$this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
		$group_privileges				 = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
		$this->data['group_privileges']	 = array();
		foreach($group_privileges as $privilege){
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}

// Get users current privilege data.
		$sql_select		 = array(
			$this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
		$sql_where		 = array(
			$this->flexi_auth->db_column('user_privilege_users', 'user_id') => $user_id);
		$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

// For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
// The array can then be used within the view to check whether the user has a specific privilege, this data allows us to then format form input values accordingly.
		$this->data['user_privileges'] = array();
		foreach($user_privileges as $privilege){
			$this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
		}

// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
	}
	/**
	 * update_group_privileges
	 * Updates the privileges for a specific user group.
	 */
	function update_group_privileges($group_id){
// Update privileges.

		if($this->input->post('update_group_privilege')){
			foreach($this->input->post('update') as $row){
				if($row['current_status'] != $row['new_status']){
// Insert new user privilege.
					if($row['new_status'] == 1){
						$this->flexi_auth->insert_user_group_privilege($group_id, $row['id']);
					}
// Delete existing user privilege.
					else{
						$sql_where = array(
							$this->flexi_auth->db_column('user_privilege_groups', 'group_id')		 => $group_id,
							$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')	 => $row['id']
						);
						$this->flexi_auth->delete_user_group_privilege($sql_where);
					}
				}
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect("module/group_privileges/$group_id");
		}

// Get data for the current user group.
		$sql_where			 = array(
			$this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);

// Get all privilege data
		$this->data['privileges'] = $this->get_privileges(true);

// Get data for the current privilege group.
		$sql_select			 = array(
			$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where			 = array(
			$this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges	 = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);

// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly.
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege){
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}

// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
	}
	/**
	 * update_project_tasks
	 * Updates the tasks for a specific project.
	 */
	function update_project_tasks($project_id){
// Update tasks.

		if($this->input->post('update_project_task')){
			foreach($this->input->post('update') as $row){
				if($row['current_status'] != $row['new_status']){
// Insert new user task.
					if($row['new_status'] == 1){
						$this->insert_project_task($project_id, $row['id']);
					}
// Delete existing user task.
					else{
						$sql_where = array(
							'project_id' => $project_id,
							'task_id'	 => $row['id']
						);
						$this->delete_project_task($sql_where);
					}
				}
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect("module/project_tasks/$project_id");
		}

// Get data for the current user project.
		$this->get_project($project_id);

// Get all task data
		$this->data['tasks'] = $this->get_tasks(true);

// Get data for the current task project.
		$sql_select	 = array('task_id');
		$sql_where	 = array('project_id' => $project_id);

		$project_tasks				 = $this->db->select($sql_select)->where($sql_where)->get('project_task')->result_array();
// For the purposes of the example demo view, create an array of ids for all the tasks that have been assigned to a task project.
// The array can then be used within the view to check whether the project has a specific task, this data allows us to then format form input values accordingly.
		$this->data['project_tasks'] = array();
		foreach($project_tasks as $task){
			$this->data['project_tasks'][] = $task['task_id'];
		}

// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
	}
	function insert_project_task($project_id, $task_id){
		$data = [
			'project_id' => $project_id,
			'task_id'	 => $task_id
		];
		return $this->db->insert('project_task', $data);
	}
	function delete_project_task($sql_where){
		return $this->db->delete('project_task', $sql_where);
	}
###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
// User Groups
###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	/**
	 * manage_user_groups
	 * The function loops through all POST data checking the 'Delete' checkboxes that have been checked, and deletes the associated user groups.
	 */
	function update_groups(){
// Delete groups.
		if($this->flexi_auth->is_privileged('Delete Groups')){
			if($delete_groups = $this->input->post('delete_group')){
				foreach($delete_groups as $group_id => $delete){
// Note: As the 'delete_group' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
					$this->flexi_auth->delete_group($group_id);
				}
// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
				redirect('auth_admin/manage_user_groups');
			}
		}
	}
	/**
	 * manage_privileges
	 * The function loops through all POST data checking the 'Delete' checkboxes that have been checked, and deletes the associated privileges.
	 */
	function update_privileges(){
// Delete privileges.
		if($delete_privileges = $this->input->post('delete_privilege')){
			foreach($delete_privileges as $privilege_id => $delete){
// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
				$this->flexi_auth->delete_privilege($privilege_id);
			}


// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/privileges/view');
		}
	}
	function update_projects(){
// Delete st_lights.
		if($this->flexi_auth->is_privileged('Delete Projects')){
			if($delete_projects = $this->input->post('delete_project')){
				foreach($delete_projects as $project_id => $delete){
// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $project_id);
// Delete privileges.
					$this->db->delete('project', $sql_where);
				}
// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
				redirect('module/projects/view');
			}
		}
	}
	function update_clock_entries(){
// Delete st_lights.
		if($this->flexi_auth->is_privileged('Delete Clock Entries')){
			if($delete_clock_entries = $this->input->post('delete_clock_entry')){
				foreach($delete_clock_entries as $clock_entry_id => $delete){
// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $clock_entry_id);
// Delete privileges.
					$this->db->delete('clock_entry', $sql_where);
				}
// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
				redirect('module/clock_entries/view');
			}
		}
	}
	function update_tasks(){
// Delete st_lights.
		if($this->flexi_auth->is_privileged('Delete Tasks')){
			if($delete_tasks = $this->input->post('delete_task')){
				foreach($delete_tasks as $task_id => $delete){
// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $task_id);
// Delete privileges.
					$this->db->delete('task', $sql_where);
				}
// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
				redirect('module/tasks/view');
			}
		}
	}
	function update_customers(){
// Delete st_lights.
		if($this->flexi_auth->is_privileged('Delete Projects')){
			if($delete_customers = $this->input->post('delete_customer')){
				foreach($delete_customers as $customer_id => $delete){
// Note: As the 'delete_privilege' input is a checkbox, it will only be present in the $_POST data if it has been checked,
// therefore we don't need to check the submitted value.
					$sql_where = array('id' => $customer_id);
// Delete privileges.
					$this->db->delete('customer', $sql_where);
				}
// Save any public or admin status or error messages to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
				redirect('module/customers/view');
			}
		}
	}
	/**
	 * insert_user_group
	 * Inserts a new user group.
	 */
	function insert_group(){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_group_name',
				'label'	 => 'Group Name',
				'rules'	 => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get user group data from input.
			$group_name	 = $this->input->post('insert_group_name');
			$group_desc	 = $this->input->post('insert_group_desc');

			$this->flexi_auth->insert_group($group_name, $group_desc, 0);

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/groups/view');
		}
	}
	/**
	 * insert_privilege
	 * Inserts a new privilege.
	 */
	function insert_privilege(){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_privilege_name',
				'label'	 => 'Privilege Name',
				'rules'	 => 'required')
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
// Get privilege data from input.
			$privilege_name	 = $this->input->post('insert_privilege_name');
			$privilege_desc	 = $this->input->post('insert_privilege_desc');

			$this->flexi_auth->insert_privilege($privilege_name, $privilege_desc);

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			redirect('module/privileges/view');
		}
	}
	function insert_project($ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_project_name',
				'label'	 => 'Project Name',
				'rules'	 => 'required'),
			array(
				'field'	 => 'insert_project_quote_amount',
				'label'	 => 'Quote Amount',
				'rules'	 => 'greater_than[0]'),
			array(
				'field'	 => 'insert_project_quote_date',
				'label'	 => 'Quote Date',
				'rules'	 => 'callback_valid_date'),
			array(
				'field'	 => 'insert_project_desc',
				'label'	 => 'Project Description',
				'rules'	 => ''),
			array(
				'field'	 => 'insert_project_quote_number',
				'label'	 => 'Quote Number',
				'rules'	 => 'int'),
			array(
				'field'	 => 'insert_project_customer_id',
				'label'	 => 'Customer',
				'rules'	 => 'int'),
			array(
				'field'	 => 'insert_project_engineer',
				'label'	 => 'Engineer',
				'rules'	 => 'int'),
			array(
				'field'	 => 'insert_project_specialist',
				'label'	 => 'Specialist',
				'rules'	 => 'int'),
		);

		$this->form_validation->set_rules($validation_rules);
		$ret = false;
		if($this->form_validation->run()){
// Get st_light data from input.

			$data					 = array();
			$data['name']			 = $this->input->post('insert_project_name');
			$data['description']	 = $this->input->post('insert_project_desc');
			$data['quote_number']	 = $this->input->post('insert_project_quote_number');
			$data['quote_date']		 = date('Y-m-d H:i:s', strtotime($this->input->post('insert_project_quote_date')));
			$data['customer_id']	 = $this->input->post('insert_project_customer_id');
			$data['engineer']		 = $this->input->post('insert_project_engineer');
			$data['specialist']		 = $this->input->post('insert_project_specialist');
			$data['quote_amount']	 = $this->input->post('insert_project_quote_amount');
			$this->db->insert('project', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($ajax){
				return $ret;
			}
			redirect('module/projects/view');
		}
	}
	function insert_clock_entry($ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_clock_entry_start',
				'label'	 => 'Start Time',
				'rules'	 => 'callback_valid_date'),
			array(
				'field'	 => 'insert_clock_entry_stop',
				'label'	 => 'Stop Time',
				'rules'	 => 'callback_valid_date'),
		);

		$this->form_validation->set_rules($validation_rules);
		$ret = false;
		if($this->form_validation->run()){
// Get st_light data from input.

			$data					 = array();
			$data['project_task_id'] = $this->input->post('insert_clock_entry_project_task_id');
			$data['user_id']		 = $this->input->post('insert_clock_entry_user_id');
			$data['start']			 = date('Y-m-d H:i:s', strtotime($this->input->post('insert_clock_entry_start')));
			$data['stop']			 = date('Y-m-d H:i:s', strtotime($this->input->post('insert_clock_entry_stop')));
			$this->db->insert('clock_entry', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($ajax){
				return $ret;
			}
			redirect('module/clock_entries/view');
		}
	}
	function insert_task($ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_task_name',
				'label'	 => 'Task Name',
				'rules'	 => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);
		$ret = false;
		if($this->form_validation->run()){
// Get st_light data from input.

			$data				 = array();
			$data['name']		 = $this->input->post('insert_task_name');
			$data['description'] = $this->input->post('insert_task_desc');
			$this->db->insert('task', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($ajax){
				return $ret;
			}
			redirect('module/tasks/view');
		}
	}
	function insert_customer($ajax = false){
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'insert_customer_company',
				'label'	 => 'Company Name',
				'rules'	 => 'required')
		);

		$this->form_validation->set_rules($validation_rules);
		$ret = false;
		if($this->form_validation->run()){
// Get st_light data from input.

			$data				 = array();
			$data['company']	 = $this->input->post('insert_customer_company');
			$data['first_name']	 = $this->input->post('insert_customer_first_name');
			$data['last_name']	 = $this->input->post('insert_customer_last_name');
			$this->db->insert('customer', $data);

			$ret = ($this->db->affected_rows() == 1)?$this->db->insert_id():FALSE;
			if($ret){
				$this->flexi_auth_model->set_status_message('update_successful', 'config');
			}else{
				$this->flexi_auth_model->set_error_message('update_unsuccessful', 'config');
			}

// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

// Redirect user.
			if($ajax){
				return $ret;
			}
			redirect('module/customers/view');
		}
	}
	public function make_select($what, $table, $filters = array()){
		$cfg		 = $this->config->item('controller_table_conversion');
		$function	 = 'get_'.$cfg[$table]['controller'];
		if(is_array($filters) && count($filters) > 0){
			foreach($filters as $db_function => $filter){
				$this->db->$db_function($filter);
			}
		}
		if($function == 'get_defect_types'){//ugh i hate this but i will need to re-vamp crap
			$results = $this->$function(0, true);
		}else{
			$results = $this->$function(true);
		}
		if(is_array($results) && count($results) > 0){
			$options[''] = 'Please Select...';
			foreach($results as $result){
				$options[$result[$cfg[$what]['view_vars']['select'][0]['value']]] = $result[$cfg[$what]['view_vars']['select'][0]['display']];
			}
			$this->data[$cfg[$what]['view_vars']['select'][0]['data_key']] = $options;
			return true;
		}
		return false;
	}
	public function analyze_clock_entries_report(){
		$fields	 = [
			'user_accounts.upro_first_name first_name',
			'user_accounts.upro_last_name last_name',
			'project.name project_name',
			'task.name task_name',
			'clock_entry.start',
			'clock_entry.stop'
		];
		$this->db->select($fields);
		$this->db->from('clock_entry');
		$this->db->join('user_accounts', 'user_accounts.uacc_id = clock_entry.user_id', 'LEFT');
		$this->db->join('project_task', 'project_task.id = clock_entry.project_task_id', 'LEFT');
		$this->db->join('project', 'project.id = project_task.project_id', 'LEFT');
		$this->db->join('task', 'task.id = project_task.task_id', 'LEFT');
		$this->db->order_by('clock_entry.start DESC');
		$this->db->order_by('clock_entry.stop DESC');
		$this->db->order_by('project.name');
		$this->db->order_by('task.name');
		$results = $this->db->get()->result_array();

		$this->data['results'] = $results;
	}
	public function analyze_clock_entries_per_project_report(){
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
		$this->data['results']			 = [];
		$extras							 = [];
		$extras['task_where']			 = "project_task.id";
		$extras['task_where_name']		 = "project_task.id AS task_id";
		if($this->input->post()){
			$results = $this->clock_report($this->input->post(), $extras);
			$var	 = [];
			foreach($results as $result){
				$pid	 = $result['project_id'];
				$tid	 = $result['task_id'];
				$uid	 = $result['user_id'];
				$time	 = strtotime($result['stop']) - strtotime($result['start']);

				$var[$pid][$tid][$uid][] = $time;
			}
			$this->data['results'] = $var;
		}
	}
	public function analyze_clock_entries_per_task_report(){
		$users				 = $this->modules->get_users(true);
		$user_options		 = array();
		$user_options['']	 = 'Please Select...';
		foreach($users as $user){
			$user_options[$user['uacc_id']] = $user['upro_first_name'].' '.$user['upro_last_name'];
		}
		$this->data['user_options'] = $user_options;

		$tasks				 = $this->modules->get_tasks(true);
		$task_options		 = array();
		$task_options['']	 = 'Please Select...';
		foreach($tasks as $task){
			$task_options[$task['id']] = $task['name'];
		}
		$this->data['task_options']	 = $task_options;
		$this->data['results']		 = [];
		$this->load->library('form_validation');

// Set validation rules.
		$validation_rules = array(
			array(
				'field'	 => 'task_id',
				'label'	 => 'Task',
				'rules'	 => 'required|int'),
			array(
				'field'	 => 'user_id',
				'label'	 => 'User',
				'rules'	 => 'int'),
			array(
				'field'	 => 'date_le',
				'label'	 => 'Date From',
				'rules'	 => 'callback_valid_date'),
			array(
				'field'	 => 'date_ge',
				'label'	 => 'Date To',
				'rules'	 => 'callback_valid_date'),
		);

		$this->form_validation->set_rules($validation_rules);

		if($this->form_validation->run()){
			$extras['order_by']	 = "project_task.task_id,clock_entry.user_id";
			$results			 = $this->clock_report($this->input->post(), $extras);
			$var				 = [];
			foreach($results as $result){
				$tid	 = $result['task_id'];
				$uid	 = $result['user_id'];
				$time	 = strtotime($result['stop']) - strtotime($result['start']);

				$var[$tid][$uid][] = $time;
			}
			$this->data['results'] = $var;
		}
	}
	public function clock_report($data = array(), $extras = array()){
		$project_id	 = (isset($data['project_id']) && $data['project_id'] != '')?$data['project_id']:false;
		$task_id	 = (isset($data['task_id']) && $data['task_id'] != '')?$data['task_id']:false;
		$user_id	 = (isset($data['user_id']) && $data['user_id'] != '')?$data['user_id']:false;
		$date_le	 = (isset($data['date_le']) && $data['date_le'] != '')?$data['date_le']:false;
		$date_ge	 = (isset($data['date_ge']) && $data['date_ge'] != '')?$data['date_ge']:false;

		$order			 = (isset($extras['order_by']) && $extras['order_by'] != '')?$extras['order_by']:"project_task.project_id,project_task.task_id,clock_entry.user_id";
		$status			 = (isset($extras['status']) && $extras['status'] != '')?$extras['status']:"finished";
		$limit			 = intval((isset($extras['limit']) && $extras['limit'] != '')?$extras['limit']:0);
		$task_where		 = (isset($extras['task_where']) && $extras['task_where'] != '')?$extras['task_where']:'project_task.task_id';
		$task_where_name = (isset($extras['task_where_name']) && $extras['task_where_name'] != '')?$extras['task_where_name']:'project_task.task_id';

		if($project_id){
			$sql_where['project_task.project_id'] = $project_id;
		}
		if($task_id){
			$sql_where[$task_where] = $task_id;
		}
		if($user_id){
			$sql_where['clock_entry.user_id'] = $user_id;
		}
		if($date_le && $date_ge){
			$sql_where["clock_entry.start BETWEEN '$date_le' AND '$date_ge'"] = NULL;
		}
		switch($status){
			case'unfinished':
				$sql_where["clock_entry.stop"]	 = 0;
				break;
			default:
			case'finished':
				$sql_where["clock_entry.stop >"] = 0;
				break;
		}
		$fields = [
			'project_task.id AS project_task_id',
			'project_task.project_id',
			$task_where_name,
			'clock_entry.user_id',
			'clock_entry.id AS clock_entry_id',
			'clock_entry.start',
			'clock_entry.stop',
		];
		$this->db->select($fields);
		$this->db->from('clock_entry');
		$this->db->join('project_task', 'project_task.id = clock_entry.project_task_id', 'LEFT');
		if($limit > 0){
			$this->db->limit($limit);
		}
		$results = $this->db->where($sql_where)->order_by($order)->get()->result_array();
		return $results;
	}
	public function format_seconds($seconds){
		$time_string = "";
		$days		 = 0;
		$hours		 = 0;
		$minutes	 = 0;
		if($seconds >= 86400){
			$days	 = floor($seconds / 86400);
			$seconds = $seconds % 86400;
			$time_string.="$days  day";
			if($days > 1){
				$time_string.="s";
			}
			if($seconds > 0){
				$time_string.=", ";
			}
		}
		if($seconds >= 3600){
			$hours	 = floor($seconds / 3600);
			$seconds = $seconds % 3600;
			$time_string.="$hours  hour";
			if($hours != 1){
				$time_string.="s";
			}
			if($seconds > 0){
				$time_string.=", ";
			}
		}
		if($seconds >= 60){
			$minutes = floor($seconds / 60);
			$seconds = $seconds % 60;
			$time_string.="$minutes  minute";
			if($minutes != 1){
				$time_string.="s";
			}
			if($seconds > 0){
				$time_string.=", ";
			}
		}
		if($seconds > 0){
			$time_string.="$seconds second";
			if($seconds != 1){
				$time_string.="s";
			}
		}
		return $time_string;
	}
}

/* End of file demo_auth_admin_model.php */
/* Location: ./application/models/demo_auth_admin_model.php */