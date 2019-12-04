<?php
class User extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model', 'user_model');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $result = $this->user_model->get([
            'name' => $username
        ]);
        $this->output->set_content_type('application_json');
        // Set session user data
        if ($result) {
            if(password_verify($password, $result[0]['password'])) {
                $this->session->set_userdata(['id' => $result[0]['id']]);
                $this->output->set_output(json_encode(['result' => 1]));
                return false;
            }
            $this->output->set_output(json_encode(['result' => 0]));
        }
            $this->output->set_output(json_encode(['result' => 0]));
        return false;
    }
    public function update(){
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $result = $this->user_model->update([
            // Hash the password with sha256 and pre-defined SALT
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ], $id);
        $this->output->set_content_type('application_json');
        if($id) {
            $this->output->set_output(json_encode(['result' => 1]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0]));
    }
}