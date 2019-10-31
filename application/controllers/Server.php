<?php
    class Server extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->model('Server_model', 'server_model');
        }

        /**
         * get
         *
         * @return void
         */
        public function get() {
            $id = $this->input->get('id');

            $result = $this->server_model->get($id);

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
            $server_name = $this->input->post('name');
            $server_ip = $this->input->post('ip');
            $server_id = $this->input->post('id');  

            // Parse data array to the server_model func insert();
            $result = $this->Server_model->insert([
                'name' => $server_name,
                'ip' => $server_ip,
                'id' => $server_id
            ]);
            
            // Set output data type to json
            $this->output->set_content_type('application/json');

            /**
             * If server_model returns error for duplicate, set output accordingly
             * 1 for no duplicates
             * 2 for duplicate name
             * 3 for duplicate IP address
             * 4 for duplicate ID
             * 0 for undefined error
             */
            if($result != "duplicate_name" && $result != "duplicate_ip" && $result != "duplicate_id") {
                $this->output->set_output(json_encode(['result' => 1]));
                return false;
            }
            elseif($result == "duplicate_id") {
                $this->output->set_output(json_encode(['result' => 2]));
            }
            elseif($result == "duplicate_name") {
                $this->output->set_output(json_encode(['result' => 3]));
            }
            elseif($result == "duplicate_ip") {
                $this->output->set_output(json_encode(['result' => 4]));
            }
            else {
                $this->output->set_output(json_encode(['result' => 0]));
            }

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