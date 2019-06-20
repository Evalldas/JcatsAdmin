<?php
    class Server_model extends CI_model {

        public function get($server_id = null){
            if($server_id != null && is_array($server_id)) {
                $query = $this->db->get_where('server', $server_id);
            } elseif ($server_id != null) {
                $query = $this->db->get_where('server', ['id' => $server_id]);
            } else {
                $query = $this->db->get('server');
            }
            return $query->result_array();
        }

        public function insert($data){
            $this->db->insert('server', $data);
            return $this->db->insert_id();
        }

        public function update($data, $server_id){
            $this->db->where(['id' => $server_id]);
            $this->db->update('server', $data);
            return $this->db->affected_rows();
        }

        public function delete($server_id){
            $this->db->delete('server', array('id' => $server_id));
            return $this->db->affected_rows();
        }

    }