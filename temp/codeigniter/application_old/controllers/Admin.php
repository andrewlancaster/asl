<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Admin_model');
        $this->load->helper('url_helper');
	}

	public function index(){
		$this->load->library('form_validation');
		if($this->input->post('register')){
			$this->form_validation->set_rules('email_address2', 'Email Address', 'required|valid_email');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

			if ( $this->form_validation->run() !== false ) {
				$this->load->model('admin_model');
				$res = $this
						->admin_model
						->save_user(
							$this->input->post('email_address'),
							$this->input->post('first_name'),
							$this->input->post('last_name'),
							$this->input->post('password'));
				if ($res !== FALSE) {
					# Account exists
					$this->session->set_userdata('email_address',$this->input->post('email_address'));
					redirect('welcome');
				}else{
					echo "User could not be authenticated.";
				}
			}

			$this->load->view('admin/register');
		}else{
			$session_user = $this->session->userdata('email_address');
			if (isset($session_user)) {
				redirect('welcome');
			}

			$this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

			if ( $this->form_validation->run() !== false ) {
				$this->load->model('admin_model');
				$res = $this
						->admin_model
						->verify_user(
							$this->input->post('email_address'),
							$this->input->post('password'));
				if ($res !== FALSE) {
					# Account exists
					$this->session->set_userdata('email_address',$this->input->post('email_address'));
					redirect('welcome');
				}else{
					echo "User could not be authenticated.";
				}
			}

			$this->load->view('admin/login_view');
			$this->load->view('admin/register');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('username');
		//unset($_SESSION['username']);
		$this->session->sess_destroy();
		$this->load->view('admin/login_view');
	}
}