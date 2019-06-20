<?php
    class Dashboard extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            $this->load->model("Server_model");
            $this->load->model("Client_model");
            
        }

        public function index($page = 'dashboard') {
            
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $this->load->view('header_view');
            $this->load->view('dashboard_view', $data);
            $this->load->view('footer_view');
        }

        public function manage_stations() {
            $data['clientCount'] = 0;
            $data['serverCount'] = 0;
            $data['servers'] = $this->Server_model->get();
            $data['clients'] = $this->Client_model->get();
            $this->load->view('header_view');
            $this->load->view('manage_stations_view', $data);
            $this->load->view('footer_view');
        }
    }