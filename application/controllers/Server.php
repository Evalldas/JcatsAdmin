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
            $name = $this->input->post('name');
            $ip = $this->input->post('ip');
            $id = $this->input->post('id');
            $domain_name = $this->input->post('domain_name');

            // Parse data array to the server_model func insert();
            $result = $this->server_model->insert([
                'name' => $name,
                'ip' => $ip,
                'id' => $id,
                'domain_name' => $domain_name
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
    
        /**
         * update
         *
         * @return void
         */
        public function update() {
            
            $new_id = $this->input->post('new-id');
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $ip = $this->input->post('ip');
            $domain_name = $this->input->post('domain_name');

            $result = $this->server_model->update([
                'id' => $new_id,
                'name' => $name,
                'ip' => $ip,
                'domain_name' => $domain_name
            ], $id);
    
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
    
        public function delete() {
            $id = $this->input->post('id');
            $result = $this->server_model->delete($id);
    
            redirect(base_url('dashboard/manage_stations/'));
        }
    }