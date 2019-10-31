<?php
    class Client_model extends CI_model {

        /**
         * Get clients either by id or all of them at once in the array
         *
         * @param  mixed $client_id Stations you want to get data ID. Function returns all stations if $client_id is empty
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
         * insert
         *
         * @param  mixed $data Array to store parsed data
         *
         * Usage:
         * $this->Client_model->insert(['name' => $name, 'ip' => $ip, 'server_id' => $server_id]);
         * 
         * @return void
         */
        public function insert($data){

            // Querys to check for duplicates. If query returns more than 0, that means duplicate has been found
            $check_name_duplicate_query = $this->db->get_where('client', ['name' => $data['name']]);
            $check_ip_duplicate_query = $this->db->get_where('client', ['ip' => $data['ip']]);
            
            // If no duplicates found, insert new record into the DB
            if ($check_name_duplicate_query->num_rows() == 0 && $check_ip_duplicate_query->num_rows() == 0) {
                $this->db->insert('client', $data);
                return $this->db->insert_id();
            }
            elseif($check_name_duplicate_query->num_rows() > 0) {
                return "duplicate_name"; // If name duplicate found, return err message
            }
            elseif($check_ip_duplicate_query->num_rows() > 0) {
                return "duplicate_ip"; // If name IP found, return err message
            }
            else {
                return $this->db->insert_id(); // Else return empty result for undefined error
            }
        }

        
        public function update($data, $id){
            $check_name_duplicate_query = $this->db->get_where('client', ['name' => $data['name'] && 'id !=', $id]);
            $check_ip_duplicate_query = $this->db->get_where('client', ['ip' => $data['ip'] && 'id !=', $id]);
            $this->db->where(['id' => $client_id]);
            $this->db->update('client', $data);

            // If no duplicates found, insert new record into the DB
            if ($check_name_duplicate_query->num_rows() == 0 && $check_ip_duplicate_query->num_rows() == 0) {
                $this->db->where(['id' => $client_id]);
                $this->db->update('client', $data);
                return $this->db->affected_rows();
            }
            elseif($check_name_duplicate_query->num_rows() > 0) {
                return "duplicate_name"; // If name duplicate found, return err message
            }
            elseif($check_ip_duplicate_query->num_rows() > 0) {
                return "duplicate_ip"; // If name IP found, return err message
            }
            else {
                return $this->db->affected_rows();// Else return empty result for undefined error
            }

            return $this->db->affected_rows();
        }

        public function delete($client_id){
            $this->db->delete('client', array('id' => $client_id));
            return $this->db->affected_rows();
        }

    }