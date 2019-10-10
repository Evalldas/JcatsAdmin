<?php
    class Client_model extends CI_model {

        public function get($client_id = null){
            if($client_id != null && is_array($client_id)) {
                $query = $this->db->get_where('client', $client_id);
            } elseif ($client_id != null) {
                $query = $this->db->get_where('client', ['id' => $client_id]);
            } else {
                $this->db->from('client');
                $this->db->order_by("name", "asc");
                $query = $this->db->get();
            }
            return $query->result_array();
        }

        public function insert($data, $client_name, $client_ip){
            $checkNameDuplicateQuery = $this->db->get_where('client', ['name' => $client_name]);
            $checkIpDuplicateQuery = $this->db->get_where('client', ['ip' => $client_ip]);
            if ($checkNameDuplicateQuery->num_rows() == 0 && $checkIpDuplicateQuery->num_rows() == 0) {
                $this->db->insert('client', $data);
                return $this->db->insert_id();
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