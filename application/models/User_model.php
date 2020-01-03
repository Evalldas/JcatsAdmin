<?php
class User_model extends CI_model {
    
    /**
     * @usage
     * Single rec:  $this->User_model->get(3);
     * All recs:    $this->User_model->get();
     */
    public function get($id = null){
        if($id != null && is_array($id)) {
            $query = $this->db->get_where('user', $id);
        } elseif ($id != null) {
            $query = $this->db->get_where('user', ['id' => $id]);
        } else {
            $query = $this->db->get('user');
        }
        return $query->result_array();
    }
    /**
     * @param array $data
     * 
     * @usage $result = $this->User_model->insert(['email' => 'user@email.com']);
     */
    public function insert($data){
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }
    /**
     * @param array $data
     * @param       $id
     * 
     * @usage $this->User_model->update(['email' => 'user@mail.com'], 3);
     */
    public function update($data, $id){
        $this->db->where(['id' => $id]);
        $this->db->update('user', $data);
        return $this->db->affected_rows();
    }
    /**
     * @param $id
     * 
     * @usage $this->User_model->delete(1);
     */
    public function delete($id){
        $this->db->delete('user', array('id' => $id));
        return $this->db->affected_rows();
    }

    /**
     * get_roles
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function get_roles(){
        $query = $this->db->get('role');
        return $query->result_array();
    }
}

