<?php
    class Dashboard extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            $this->load->model("Server_model");
            $this->load->model("Client_model");
            $user_id = $this->session->userdata('id');
            
        }

        public function index($page = 'dashboard') {
            $user_id = $this->session->userdata('id');
            if(!$user_id) {
                $this->logout();
            }
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $this->load->view('header_view');
            $this->load->view('navbar_view');
            $this->load->view('dashboard_view', $data);
            $this->load->view('footer_view');
        }

        public function manage_stations($page = 'dashboard/manage_stations') {
            $user_id = $this->session->userdata('id');
            if(!$user_id) {
                $this->logout();
            }
            $data['clientCount'] = 0;
            $data['serverCount'] = 0;
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $this->load->view('header_view');
            $this->load->view('navbar_view');
            $this->load->view('manage_stations_view', $data);
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