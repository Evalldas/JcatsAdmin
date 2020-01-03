<?php
class User extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model', 'user_model');
    }

    /**
     * login
     *
     * @return void
     */
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
                $this->session->set_userdata(['role' => $result[0]['role']]);
                $this->session->set_userdata(['name' => $result[0]['name']]);
                $this->output->set_output(json_encode(['result' => 1]));
                return false;
            }
            $this->output->set_output(json_encode(['result' => 0]));
        }
            $this->output->set_output(json_encode(['result' => 0]));
        return false;
    }

    /**
     * create
     *
     * @return void
     */
    public function create() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        $result = $this->user_model->insert([
            'name' => $username,
            'role' => $role,
            // Hash the password with sha256 and pre-defined SALT
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        $this->output->set_content_type('application_json');
        if($result) {
            $this->output->set_output(json_encode(['result' => 1]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0]));
    }

    public function update(){
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $role = $this->input->post('role');

        if($password && $role) {
            $result = $this->user_model->update([
                'role' => $role,
                // Hash the password with sha256 and pre-defined SALT
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ], $id);
        } elseif (!$password) {
            $result = $this->user_model->update([
                'role' => $role,
            ], $id);
        } elseif (!$role) {
            $result = $this->user_model->update([
                // Hash the password with sha256 and pre-defined SALT
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ], $id);
        }
        
        $this->output->set_content_type('application_json');
        if($id) {
            $this->output->set_output(json_encode(['result' => 1]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0]));
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete() {
        $user_id = $this->input->post('id');
        $result = $this->user_model->delete($user_id);

        redirect(base_url('dashboard/admin_tools/'));
    }
}