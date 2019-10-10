<?php
    class Client_model extends CI_model {

        /**
         * Get clients either by id or all of them at once in the array
         *
         * @param  mixed $client_id
         * 
         * Usage:
         * For single station:      $this->Client_model->get(1);
         * For multiple stations:   $this->Client_model->get(1, 2, 3);
         * For all stations:        $this->Client_model->get();
         * 
         * @return void
         */
        public function get($client_id = null){
            if($client_id != null && is_array($client_id)) {
                $query = $this->db->get_where('client', $client_id); // If given multiple id's, return array of requested records
            } elseif ($client_id != null) {
                $query = $this->db->get_where('client', ['id' => $client_id]); // If given single id, return single requested entry
            } else {
                // If requested all ordering by name must be done 
                $this->db->from('client'); // From table 'client'
                $this->db->order_by('CHAR_LENGTH(name), name', "asc"); // Order by lenght of the name and alphabet
                $query = $this->db->get(); // Send query to the db
            }
            return $query->result_array(); // If failed to do anything, return empty result_array
        }

        /**
         * Insert new station in to the database
         *
         * @param  mixed $data          
         * @param  mixed $client_name
         * @param  mixed $client_ip
         *
         * @return void
         */
        public function insert($data){
            //$checkNameDuplicateQuery = $this->db->get_where('client', ['name' => $client_name]);
            //$checkIpDuplicateQuery = $this->db->get_where('client', ['ip' => $client_ip]);
            $check_name_duplicate_query = $data[0]['name'];
            $check_ip_duplicate_query = $data[0]['ip'];
            if ($checkNameDuplicateQuery->num_rows() == 0 && $checkIpDuplicateQuery->num_rows() == 0) {
                $this->db->insert('client', $data);
                return $this->db->insert_id();
            }
            elseif($check_name_duplicate_query->num_rows() == 1) {
                return "duplicate_name";
            }
            elseif($check_name_duplicate_query->num_rows() == 1) {
                return "duplicate_ip";
            }
            else {
                return $this->db->insert_id();
            }
        }

        public function update($data, $client_id){
            $this->db->where(['id' => $client_id]);
            $this->db->update('client', $data);
            return $this->db->affected_rows();
        }

        public function delete($client_id){
            $this->db->delete('client', array('id' => $client_id));
            return $this->db->affected_rows();
        }

    }