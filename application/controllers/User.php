<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('api_model');
        $this->load->helper('url');
        $this->load->helper('text');
    }

    public function adminUsers()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        $item = array();
        if ($isValidToken) {
            $users = $this->user_model->get_admin_users();
            foreach ($users as $user) {
                $item[] = array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'token' => $user->token,
                    'is_active' => $user->is_active,
                    'roles' => $user->roles,
                    'created_at' => $user->created_at
                );
            }

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($item));
        }
    }

    public function adminUser($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $user = $this->user_model->get_admin_user($id);

            $item = array(
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'password' => $user->password,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'token' => $user->token,
                'is_active' => $user->is_active,
                'roles' => $user->roles,
                'created_at' => $user->created_at
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($item));
        }
    }

    public function createUser()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $is_active = $this->input->post('is_active');
            $roles = 'user';

            $userData = array(
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'is_active' => $is_active,
                'roles' => $roles,
                'created_at' => date('Y-m-d H:i:s', time()),
                'token' => bin2hex(random_bytes(16))
            );

            $id = $this->user_model->insertUser($userData);

            $response = array(
                'status' => 'success'
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    public function updateUser($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $user = $this->user_model->get_admin_user($id);

            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $is_active = $this->input->post('is_active');
            $roles = $this->input->post('roles');

            $userData = array(
                'username' => $username,
                'email' => $email,
                'password' => md5($password),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'is_active' => $is_active,
                'roles' => $roles
            );

            $this->user_model->updateUser($id, $userData);

            $response = array(
                'status' => 'success'
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    public function deleteUser($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $user = $this->user_model->get_admin_user($id);

            $this->user_model->deleteUser($id);

            $response = array(
                'status' => 'success'
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }
}

