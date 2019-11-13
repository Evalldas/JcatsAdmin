<?php
    class Server_model extends CI_model {

        /**
         * Get servers either by id or all of them at once in the array
         *
         * @param  mixed $server_id Stations you want to get data ID. Function returns all stations if $server_id is empty
         * 
         * Usage:
         * For single station:      $this->Server_model->get(1);
         * For multiple stations:   $this->Server_model->get(1, 2, 3);
         * For all stations:        $this->Server_model->get();
         * 
         * @return void
         */
        public function get($server_id = null){
            if($server_id != null && is_array($server_id)) {
                $query = $this->db->get_where('server', $server_id); // If given multiple id's, return array of requested records
            } elseif ($server_id != null) {
                $query = $this->db->get_where('server', ['id' => $server_id]); // If given single id, return single requested entry
            } else {
                // If requested all ordering by ID must be done 
                $this->db->from('server'); // From table 'server'
                $this->db->order_by("id", "asc"); // Order by ID
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
         * $this->Server_model->insert(['name' => $name, 'ip' => $ip, 'id' => $server_id]);
         * 
         * @return void
         */
        public function insert($data){

            // Querys to check for duplicates. If query returns more than 0, that means duplicate has been found
            $check_name_duplicate_query = $this->db->get_where('server', ['name' => $data['name']]);
            $check_ip_duplicate_query = $this->db->get_where('server', ['ip' => $data['ip']]);
            $check_id_duplicate_query = $this->db->get_where('server', ['id' => $data['id']]);
            
            // If no duplicates found, insert new record into the DB
            if ($check_name_duplicate_query->num_rows() == 0 && $check_ip_duplicate_query->num_rows() == 0 && $check_id_duplicate_query->num_rows() == 0) {
                $this->db->insert('server', $data);
                return $this->db->insert_id();
            }
            elseif($check_name_duplicate_query->num_rows() > 0) {
                return "duplicate_name"; // If name duplicate found, return err message
            }
            elseif($check_ip_duplicate_query->num_rows() > 0) {
                return "duplicate_ip"; // If name IP found, return err message
            }
            elseif($check_id_duplicate_query->num_rows() > 0) {
                return "duplicate_id"; // If name IP found, return err message
            }
            else {
                return $this->db->insert_id(); // Else return empty result for undefined error
            }
        }

        public function update($data, $id){
            $this->db->where(['id' => $id]);
            $this->db->update('server', $data);
            return $this->db->affected_rows();
        }

        public function delete($server_id){
            $this->db->delete('server', array('id' => $server_id));
            return $this->db->affected_rows();
        }

    }