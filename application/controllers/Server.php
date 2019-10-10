<?php
    class Server extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->model('Server_model');
        }

        public function create() {
            $server_id = $this->input->post('id');
            $server_name = $this->input->post('name');
            $server_ip = $this->input->post('ip');    
            $result = $this->Server_model->insert([
                'id' => $server_id,
                'name' => $server_name,
                'ip' => $server_ip
            ], $server_name, $server_ip);
    
            redirect(site_url('dashboard/manage_stations/'));
        }
    
        public function update() {
            $new_server_id = $this->input->post('new-id');
            $server_id = $this->input->post('id');
            $server_name = $this->input->post('name');
            $server_ip = $this->input->post('ip');
            $result = $this->Server_model->update([
                'id' => $new_server_id,
                'name' => $server_name,
                'ip' => $server_ip
            ], $server_id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    
        public function delete() {
            $server_id = $this->input->post('id');
            $result = $this->Server_model->delete($server_id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    }