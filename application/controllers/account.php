<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends LF_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		if($this->flexi_auth->is_logged_in()){
			redirect('home/home');
		}elseif(method_exists($this, $this->task_key)){
			$function = $this->task_key;
			if($function == 'index'){
				$function = 'login';
			}
			$this->$function();
		}else{
			die('Why are you here?');
		}
	}
	public function login(){
		if($this->input->post('login_user')){
			$this->load->model('flexi_auth_model');
			$identity	 = $this->input->post('login_identity');
			$password	 = $this->input->post('login_password');
			$remember_me = $this->input->post('remember_me');
			//var_dump($this->flexi_auth->is_logged_in(), $this->flexi_auth_model->login($identity, $password, $remember_me));
			if($this->flexi_auth_model->login($identity, $password, $remember_me)){
				redirect('home/dashboard');
			}else{
				$this->data['message'] = "We're sorry, we could not log you in with those credentials.";
			}
		}
		$this->data['message']	 = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		$content				 = $this->load->view('account/login', $this->data, true);
		$this->data['content']	 = $content;
		$this->load->view('tpl/structure', $this->data);
	}
	public function logout(){
		$this->flexi_auth->logout();
		redirect('account');
	}
	/**
	 * forgotten_password
	 * Send user an email to verify their identity. Via a unique link in this email, the user is redirected to the site so they can then reset their password.
	 * In this demo, this page is accessed via a link on the login page.
	 *
	 * Note: This is step 1 of an example of allowing users to reset a forgotten password manually.
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 */
	function forgotten_password(){
		$this->load->library('form_validation');
		// If the 'Forgotten Password' form has been submitted, then email the user a link to reset their password.
		$this->form_validation->set_rules('forgot_password_identity', 'Identity (Email / Login)', 'required');

		// Run the validation.
		if($this->form_validation->run()){
			$identity = $this->input->post('forgot_password_identity');
			$this->flexi_auth->forgotten_password($identity);
		}

		// Get any status message that may have been set.
		$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];

		$this->data['content'] = $this->load->view('account/forgotten_password', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
	/**
	 * manual_reset_forgotten_password
	 * This is step 2 (The last step) of an example of allowing users to reset a forgotten password manually.
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/manual_reset_forgotten_password/...'.
	 */
	function manual_reset_forgotten_password($user_id = FALSE, $token = FALSE){
		// If the 'Change Forgotten Password' form has been submitted, then update the users password.
		$this->load->library('form_validation');

		// Set validation rules
		// The custom rule 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'required|validate_password|matches[confirm_new_password]'),
			array('field' => 'confirm_new_password', 'label' => 'Confirm Password', 'rules' => 'required')
		);

		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if($this->form_validation->run()){
			// Get password data from input.
			$new_password = $this->input->post('new_password');

			// The 'forgotten_password_complete()' function is used to either manually set a new password, or to auto generate a new password.
			// For this example, we want to manually set a new password, so ensure the 3rd argument includes the $new_password var, or else a  new password.
			// The function will then validate the token exists and set the new password.
			$this->flexi_auth->forgotten_password_complete($user_id, $token, $new_password);
			// Get any status message that may have been set.
			$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
			redirect('account');
		}
		$this->data['content'] = $this->load->view('account/update_password', $this->data, true);
		$this->load->view('tpl/structure', $this->data);
	}
}
