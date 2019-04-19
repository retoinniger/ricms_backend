<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model');
		$this->load->helper('url');
		$this->load->helper('text');
	}

    public function category($id)
	{
		header("Access-Control-Allow-Origin: *");
		
		$category = $this->api_model->get_category($id);

		$category = array(
			'id' => $category->id,
			'category_name' => $category->category_name
		);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($category));
    }
    
    public function categories()
    {
        header("Access-Control-Allow-Origin: *");

        $categories = $this->api_model->get_categories();

        $item = array();
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $item[] = array(
                    'id' => $cat->id,
                    'category_name' => $cat->category_name
                );
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($item));
    }

	public function contact()
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		$formdata = json_decode(file_get_contents('php://input'), true);

		if( ! empty($formdata)) {

			$name = $formdata['name'];
			$email = $formdata['email'];
			$phone = $formdata['phone'];
			$message = $formdata['message'];

			$contactData = array(
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'message' => $message,
				'created_at' => date('Y-m-d H:i:s', time())
			);
			
			$id = $this->api_model->insert_contact($contactData);

			$this->sendemail($contactData);
			
			$response = array('id' => $id);
		}
		else {
			$response = array('id' => '');
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	public function sendemail($contactData)
	{
		$message = '<p>Hi, <br />Some one has submitted contact form.</p>';
		$message .= '<p><strong>Name: </strong>'.$contactData['name'].'</p>';
		$message .= '<p><strong>Email: </strong>'.$contactData['email'].'</p>';
		$message .= '<p><strong>Phone: </strong>'.$contactData['phone'].'</p>';
		$message .= '<p><strong>Name: </strong>'.$contactData['message'].'</p>';
		$message .= '<br />Thanks';

		$this->load->library('email');

		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';

		$this->email->initialize($config);

		$this->email->from('demo@example.com', 'RI CMS');
		$this->email->to('demo2@example.com');
		$this->email->cc('cc@example.com');
		$this->email->bcc('bcc@example.com');

		$this->email->subject('Contact Form');
		$this->email->message($message);

		$this->email->send();
	}

	public function login() 
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");
		header('Access-Control-Allow-Headers: Accept,Accept-Language,Content-Language,Content-Type');

		$formdata = json_decode(file_get_contents('php://input'), true);

		$username = $formdata['username'];
		$password = $formdata['password'];

		$user = $this->api_model->login($username, $password);

		if($user) {
			$response = array(
				'user_id' => $user->id,
				'first_name' => $user->first_name,
				'last_name' => $user->last_name,
                'token' => $user->token,
                'roles' => $user->roles
			);
		}
		else {
		  	$response = array();
		}

		$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response));
    }
    
    public function adminCategories()
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: authorization, Content-Type");

		$token = $this->input->get_request_header('Authorization');

		$isValidToken = $this->api_model->checkToken($token);

		$item = array();
		if($isValidToken) {
			$categories = $this->api_model->get_admin_categories();
			foreach($categories as $category) {
				$item[] = array(
					'id' => $category->id,
					'category_name' => $category->category_name
				);
			}

			$this->output
				->set_status_header(200)
				->set_content_type('application/json')
				->set_output(json_encode($item)); 
		}
    }

    public function adminCategory($id)
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: authorization, Content-Type");

		$token = $this->input->get_request_header('Authorization');

		$isValidToken = $this->api_model->checkToken($token);

		if($isValidToken) {

			$category = $this->api_model->get_admin_category($id);

			$item = array(
				'id' => $category->id,
				'category_name' => $category->category_name
			);
			
			$this->output
				->set_status_header(200)
				->set_content_type('application/json')
				->set_output(json_encode($item)); 
		}
	}
    
    public function createCategory()
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Request-Headers: GET,POST,OPTIONS,DELETE,PUT");
		header("Access-Control-Allow-Headers: authorization, Content-Type");

		$token = $this->input->get_request_header('Authorization');

		$isValidToken = $this->api_model->checkToken($token);

		if($isValidToken) {

			$category_name = $this->input->post('category_name');

			$categoryData = array(
                'category_name' => $category_name
            );

            $id = $this->api_model->insertCategory($categoryData);

            $response = array(
                'status' => 'success'
            );

			$this->output
				->set_status_header(200)
				->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
	}

	public function updateCategory($id)
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: authorization, Content-Type");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		$token = $this->input->get_request_header('Authorization');

		$isValidToken = $this->api_model->checkToken($token);

		if($isValidToken) {

			$category = $this->api_model->get_admin_category($id);
			
			$category_name = $this->input->post('category_name');

            $categoryData = array(
                'category_name' => $category_name
            );

            $this->api_model->updateCategory($id, $categoryData);

            $response = array(
                'status' => 'success'
            );

			$this->output
				->set_status_header(200)
				->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
	}

	public function deleteCategory($id)
	{
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: authorization, Content-Type");

		$token = $this->input->get_request_header('Authorization');

		$isValidToken = $this->api_model->checkToken($token);

		if($isValidToken) {

			$category = $this->api_model->get_admin_category($id);

			$this->api_model->deleteCategory($id);

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
