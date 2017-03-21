<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		
	}



	public function index()
	{
		//instruct.txt step 3~7
		$this->load->view('homeV');
	}

	public function about()
	{
		//instruct.txt step 3~7
		$this->load->view('aboutV');	
	}

	public function contact()
	{
		//instruct.txt step 3~7	
		$this->load->view('contactV');
	}

	public function order()
	{
		//instruct.txt step 3~7
		$this->load->view('orderV');	
	}

	public function signin()
	{
		$data = NULL ;
		// create the data object
		//$data = new stdClass();
		
		//$this->load->helper('form');
		$this->load->library('form_validation');

		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');
		//$this->form_validation->set_rules('email', 'email', 'required');
		
		if ($this->form_validation->run() == false) {
			$this->load->view('signinV', $data);

		} else {
			// set variables from the form
			$username = $this->input->post('username'); //echo $username; echo '<br>';
			$password = $this->input->post('password'); //echo $password; echo '<br>';
			//$email = $this->input->post('email'); //echo $email; echo '<br>';
			
			$this->load->model('user_model');
			$query = $this->user_model->resolve_user_login($username, $password);

			
			if ($query->num_rows() == 1){
				$data = NULL ;
				//echo $query->result()->row()->username;
				foreach ($query->result() as $row)
				{
				   $data['username'] =  $username; 
				}

				//echo 'login ok yesrname=' . $username . '<br>';
				
				$_SESSION["user"]=$username;
				$this->load->view('signinOK_v', $data);
				//header("Location: ./index.php");

			}else{
				
				 echo '<h1 style=" color:red; "> Your username or password is wrong, ' . $username . '<br></h1>';
				 $this->load->view('signinV', $data);	

			}
			
	    }
	    	/*
			if ($this->user_model->resolve_user_login($username, $password)) {
				
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);
				
				// set session user datas
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;
				
				// user login ok
				$this->load->view('signinOK_v');	
			} else {
				
				// login failed
				$data->error = 'Wrong username or password.';
				
				// send error to the view
				$this->load->view('signinV');	
				
			}  */
			 
		//$this->load->view('signinV');	
	}
    
	public function signup()
	{	
		$data = NULL ;
		// create the data object
		//$data = new stdClass();
		
		//$this->load->helper('form');
		$this->load->library('form_validation');

		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');
		//$this->form_validation->set_rules('email', 'email', 'required');
		
		if ($this->form_validation->run() == false) {
			$this->load->view('signupV', $data);

		} else {
			// set variables from the form
			$username = $this->input->post('username'); //echo $username; echo '<br>';
			$email    = $this->input->post('email');  //echo $email; echo '<br>';
			$password = $this->input->post('password');  //echo $password; echo <br>';
			$passconf = $this->input->post('password_confirm');

			$this->load->model('user_model');		
			if ($this->user_model->create_user($username, $email, $password) &&$password ==  $passconf ) {
				//$_SESSION["user"]=null;
				
				$this->load->view('signupOK', $data);
				
			} else {
					
				// user creation failed, this should never happen
				$data->error = 'There was a problem creating your new account. Please try again.';
				$this->load->view('signupV', $data);
			}
			
	    }
		/*
		// create the data object
		$data = new stdClass();

		$this->load->helper('form');
		$this->load->library('form_validation');


			
		// set variables from the form
		$username = $this->input->post('username'); //echo $username; echo '<br>';
		$email    = $this->input->post('email');  //echo $email; echo '<br>';
		$password = $this->input->post('password');  //echo $password; echo <br>';
	
		$this->load->model('user_model');		
		if ($this->user_model->create_user($username, $email, $password)) {
			//$_SESSION["user"]=null;
			
			$this->load->view('signupOK', $data);
			
		} else {
				
			// user creation failed, this should never happen
			$data->error = 'There was a problem creating your new account. Please try again.';
			$this->load->view('signup', $data);
		}
			
		//$this->user_model->email();

		$this->load->view('signupV');
		*/		
	}
	
	public function activate(){
		
		$user = $this->input->get('user');
		$msg  = $this->input->get('msg');
		
		$this->load->model('user_model');
		$query = $this->user_model->user_activate($user, $msg);
		
		if ($query!=1) {
			
			$data = array(
               'message' => $query
			);
			
			$this->load->view('activateEr',$data);
		} else {
			header("Location: .?'$query'");
	    }
		
	}
	
	
	public function signout()
	{
		$_SESSION["user"]=null;
		unset($_SESSION['user']);
		header("Location: .");
	}
	
	public function test()
	{
		$this->load->model('user_model');
		$query = $this->user_model->email_activate('jifan','stan6805@gmail.com','jifan');
	}

}
