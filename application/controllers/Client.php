<?php
    class Client extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->model('Client_model');
        }

        public function create() {
            $client_name = $this->input->post('name');
            $client_ip = $this->input->post('ip');  
            $server_id = 0;
            $result = $this->Client_model->insert([
                'name' => $client_name,
                'ip' => $client_ip,
                'server_id' => $server_id
            ], $client_name, $client_ip);
    
            redirect(site_url('dashboard/manage_stations/'));
        }
    
        public function update() {
            $client_id = $this->input->post('id');
            $client_name = $this->input->post('name');
            $client_ip = $this->input->post('ip');
            $server_id = $this->input->post('server_id');
            $result = $this->Client_model->update([
                'name' => $client_name,
                'ip' => $client_ip,
                'server_id' => $server_id
            ], $client_id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    
        public function delete() {
            $client_id = $this->input->post('id');
            $result = $this->Client_model->delete($client_id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    }