<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('form', 'url'));
        $this->load->model('user_detail/user_detail');
    }

    public function index() {
        $this->load->view('login');
    }

    //Login button is triggered
    
    /*  Matches username and password recieved
     *  and redirects accordingly
     */  
    
    public function login_redirect() {
        $this->form_validation->set_rules('username', '', 'required|alpha');
        $this->form_validation->set_rules('password', '', 'required');
        $this->form_validation->set_message('alpha', 'Username can have alphabets only');
        if ($this->form_validation->run() == TRUE) {
            $user_detail = $this->login_check();
            if ($user_detail == FALSE) {
                $data['error'] = 'false';
                $this->load->view('login', $data);
            } else {
                $user_data = $this->user_detail->get_user_id();
                $sess_data = array(
                    'user_id' => $user_data[0]->id,
                    'f_name' => $user_data[0]->f_name,
                    'l_name' => $user_data[0]->l_name,
                );
                echo $this->user_detail->get_user_id();
                $this->session->set_userdata($sess_data);
                redirect('post_job/list_view');
            }
        } else {
            $this->load->view('login');
        }
    }

    //Match username and password
    public function login_check() {
        $data['username'] = $this->input->post('username');
        $data['password'] = $this->input->post('password');
        $query = $this->user_detail->get_user_data('users', $data);
        if ($query->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Sign out
    public function signout() {
        //$this->session->unset_userdata($this->session->all_users());
        $this->session->sess_destroy();
        redirect('login');
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login/login.php */