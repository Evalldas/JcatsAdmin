<?php
    class Doc extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            $this->load->model("Server_model");
            $this->load->model("Client_model");
            
        }

        public function index($page = "docs") {
            $user_id = $this->session->userdata('id');
            if(!$user_id) {
                $this->logout();
            }
            $this->load->view('header_view');
            $this->load->view('navbar_view');
            $this->load->view('doc/side_menu_view');
            $this->load->view('doc/main_doc_view');
            $this->load->view('footer_view');
        }
    }