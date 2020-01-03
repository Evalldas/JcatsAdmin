<?php
    class Dashboard extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            $this->load->model("Server_model");
            $this->load->model("Client_model");
            $this->load->model("User_model");
            $user_id = $this->session->userdata('id');
        }

        public function index($page = 'dashboard') {
            $user_id = $this->session->userdata('id');
            $user_role = $this->session->userdata('role');
            if(!$user_id) {
                $this->logout();
            }
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $data['user_role'] = $user_role;

            $this->load->view('header_view');
            $this->load->view('navbar_view', $data);
            $this->load->view('dashboard_view', $data);
            $this->load->view('footer_view');
        }

        public function manage_stations($page = 'dashboard/manage_stations') {
            $user_id = $this->session->userdata('id');
            $user_role = $this->session->userdata('role');
            if(!$user_id) {
                $this->logout();
            }
            $data['clientCount'] = 0;
            $data['serverCount'] = 0;
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $data['user_role'] = $user_role;
            $this->load->view('header_view');
            $this->load->view('navbar_view', $data);
            $this->load->view('manage_stations_view', $data);
            $this->load->view('footer_view');
        }

        public function profile_management() {
            $user_id = $this->session->userdata('id');
            $user_role = $this->session->userdata('role');
            if(!$user_id) {
                $this->logout();
            }
            $data['clientCount'] = 0;
            $data['serverCount'] = 0;
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $data['user_role'] = $user_role;
            $data['user_id'] = $user_id;
            $this->load->view('header_view');
            $this->load->view('navbar_view', $data);
            $this->load->view('profile_management_view', $data);
            $this->load->view('footer_view');
        }

        public function admin_tools() {
            $user_id = $this->session->userdata('id');
            $user_role = $this->session->userdata('role');
            if(!$user_id) {
                $this->logout();
            }
            if($user_role != 1) {
                $this->index();
            }
            $data['users'] = $this->User_model->get();
            $data['roles'] = $this->User_model->get_roles();
            $data['clientCount'] = 0;
            $data['serverCount'] = 0;
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $data['user_role'] = $user_role;
            $this->load->view('header_view');
            $this->load->view('navbar_view', $data);
            $this->load->view('admin_tools_view', $data);
            $this->load->view('footer_view');
        }

        public function logout() {
            $this->session->sess_destroy();
            redirect('/dashboard/login');
        }
    

        public function login() {
            $user_id = $this->session->userdata('id');
            if($user_id) {
                $this->index();
            }
            $this->load->view('header_view');
            $this->load->view('login_view');
            $this->load->view('footer_view');
        }


    }