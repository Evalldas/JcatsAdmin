<?php
    class Client extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->model('Client_model', 'client_model');
        }

        /**
         * get
         *
         * @return void
         */
        public function get() {
            $client_id = $this->input->get('id');

            $result = $this->client_model->get($client_id);

            $this->output->set_content_type('application/json');

            $this->output->set_output(json_encode(['result' => $result]));
        }

        /**
         * Create new station
         * 
         * Output in json format
         *
         * @return void
         */
        public function create() {

            // Get data from form through POST method
            $client_name = $this->input->post('name');
            $client_ip = $this->input->post('ip');
            $server_id = 0; // Byt default given server id 0. Later updates automatically when station connects to the network

            // Parse data array to the client_model func insert();
            $result = $this->client_model->insert([
                'name' => $client_name,
                'ip' => $client_ip,
                'server_id' => $server_id
            ]);
            
            // Set output data type to json
            $this->output->set_content_type('application/json');

            /**
             * If client_model returns error for duplicate, set output accordingly
             * 1 for no duplicates
             * 2 for duplicate name
             * 3 for duplicate IP address
             * 0 for undefined error
             */
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
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $ip = $this->input->post('ip');
            $server_id = $this->input->post('server_id');
            $result = $this->client_model->update([
                'name' => $name,
                'ip' => $ip,
                'server_id' => $server_id
            ], $id);

            // Set output data type to json
            $this->output->set_content_type('application/json');

            /**
             * If client_model returns error for duplicate, set output accordingly
             * 1 for no duplicates
             * 2 for duplicate name
             * 3 for duplicate IP address
             * 0 for undefined error
             */
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
    
        public function delete() {
            $client_id = $this->input->post('id');
            $result = $this->client_model->delete($client_id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    }