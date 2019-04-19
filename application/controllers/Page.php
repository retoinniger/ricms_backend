<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');
        $this->load->model('api_model');
        $this->load->helper('url');
        $this->load->helper('text');
    }

    public function page($slug)
    {
        header("Access-Control-Allow-Origin: *");

        $page = $this->page_model->get_page($slug);

        $pagedata = array(
            'id' => $page->id,
            'title' => $page->title,
            'description' => $page->description
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($pagedata));
    }

    public function pages()
    {
        header("Access-Control-Allow-Origin: *");

        $pages = $this->page_model->get_pages();

        $item = array();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $item[] = array(
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'is_active' => $page->is_active
                );
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($item));
    }

    public function adminPages()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        $item = array();
        if ($isValidToken) {
            $pages = $this->page_model->get_admin_pages();
            foreach ($pages as $page) {
                $item[] = array(
                    'id' => $page->id,
                    'title' => $page->title,
                    'description' => $page->description,
                    'slug' => $page->slug,
                    'is_active' => $page->is_active,
                    'created_at' => $page->created_at
                );
            }

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($item));
        }
    }

    public function adminPage($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $page = $this->page_model->get_admin_page($id);

            $item = array(
                'id' => $page->id,
                'title' => $page->title,
                'description' => $page->description,
                'slug' => $page->slug,
                'is_active' => $page->is_active,
                'created_at' => $page->created_at
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($item));
        }
    }

    public function createPage()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $title = $this->input->post('title');
            $slug = $this->input->post('slug');
            $description = $this->input->post('description');
            $is_active = $this->input->post('is_active');

            $pageData = array(
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'is_active' => $is_active,
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $id = $this->page_model->insertPage($pageData);

            $response = array(
                'status' => 'success'
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    public function updatePage($id)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: authorization, Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $page = $this->page_model->get_admin_page($id);

            $title = $this->input->post('title');
            $slug = $this->input->post('slug');
            $description = $this->input->post('description');
            $is_active = $this->input->post('is_active');

            $pageData = array(
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'is_active' => $is_active
            );

            $this->page_model->updatePage($id, $pageData);

            $response = array(
                'status' => 'success'
            );

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    public function deletePage($id)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: authorization, Content-Type");

        $token = $this->input->get_request_header('Authorization');

        $isValidToken = $this->api_model->checkToken($token);

        if ($isValidToken) {

            $page = $this->page_model->get_admin_page($id);

            $this->page_model->deletePage($id);

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
