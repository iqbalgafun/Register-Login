<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->view('login_view');		
	}

	public function cekLogin()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_cekDb');
		if($this->form_validation->run()==false){
			$this->load->view('login_view');
		}else{
			redirect('Pegawai','refresh');
		}
	}

	public function cekDb($password)
	{
		$this->load->model('user');
		$username = $this->input->post('username');
		$result = $this->user->login($username,$password);
		if($result){
			$sess_array = array();
			foreach ($result as $row) {
				$sess_array = array(
					'id'=>$row->id,
					'username'=> $row->username
				);
				$this->session->set_userdata('logged_in',$sess_array);
			}
			return true;
		}else{
			$this->form_validation->set_message('cekDb',"Login Gagal Username dan Password tidak valid");
			return false;
		}
	}

	public function create()
	{
		$this->load->helper('url','form');	
		$this->load->library('form_validation'); //untuk form validasi
		$this->form_validation->set_rules('username', 'username', 'trim|required');//trim memo
		$this->form_validation->set_rules('password', 'password', 'trim|required');
		$this->load->model('user');	
		if($this->form_validation->run()==FALSE){

			$this->load->view('register');

		}else{
			$this->user->insertRegister();
			redirect('login','refresh');
		}	
	}
	public function cekDbRegister()
	{
		$this->load->model('user');
		$username = $this->input->post('username');
		$result = $this->user->cekRegister($username);
		if($result){
			$this->form_validation->set_message('cekDbRegister',"Username sudah dipakai!");
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	/*function valid_id($username)
	{
        if ($this->user->valid_id($username) == TRUE)
        {
            $this->$this->form_validation->set_message('valid_id', "Username sudah dipakai sebelumnya");
            return FALSE;
        }
        else
        {
        	return TRUE;
        }
	}*/

	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect('login','refresh');
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */

 ?>