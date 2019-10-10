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
            ]);
    
            $this->output->set_content_type('application/json');

            if($result != "duplicate_name" && $result != "duplicate_ip") {
                $this->output->set_output(json_encode(['result' => 1]));
                return false;
            }
            elseif($result == "duplicate_name") {
                $this->output->set_output(json_encode(['result' => 2]));
            }
            elseif($result == "duplicate_ip") {
                $this->output->set_output(json_encode(['result' => 3]));
            }
            else {
                $this->output->set_output(json_encode(['result' => 0]));
            }
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