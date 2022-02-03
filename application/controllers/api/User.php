<?php

use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
     
class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('userModel');
     }
    // public function index_get(){
    //     $this->load->helper('url');
    //     $this->load->view('user/login');
    // }

    public function allUsers_get(){
        $users_data = $this->userModel->allUser();
        $data = [];
        foreach($users_data as $user){
            array_push($data, array(
                "id" => $user['id'],
                "username" => $user['userName'],
                "email" => $user['email']
            ));
        }
        
        if (!empty($data)){
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $this->response('nope', REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
    public function searchUser_post(){
        $username= $this->input->post('username');
        $user = $this->userModel->getUser($username);
        $data = [];
            array_push($data, array(
                "id" => $user['id'],
                "username" => $user['userName'],
                "email" => $user['email']
            ));
        
        if (!empty($data)){
            $this->response(['Status Code' => '200','Users' => $data], REST_Controller::HTTP_OK);
        }else{
            $this->response(['Message'=>'No users available'], REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
    public function userValidation_post(){
        $username= $this->input->post('username');
        $password= $this->input->post('password');
        $user = $this->userModel->getUser($username);
        $data = [];
        if(password_verify($password, $user['password'])){
            array_push($data, array(
                "id" => $user['id'],
                "username" => $user['userName'],
                "email" => $user['email']
            ));
        }
        
        
        if (!empty($data)){
            // $this->load->view(,$data);
            // 
            $this->response(['Status_Code'=>'200','Message'=>'Sucess','user'=>$data],REST_Controller::HTTP_OK);
        }else{
            $this->response(['Status_Code'=>'200','Message'=>'Authentication Failed'], REST_Controller::HTTP_OK);
        }
    }
    public function userCreate_post(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $available = $this->userModel->getUser($username);
        if(empty($available)){
            $password = password_hash($password, PASSWORD_DEFAULT);
            $user_data=[
                'userName' => $username,
                'password' => $password,
                'email' => $email
            ];
            $this->userModel->addUser($user_data);    
            $new_user = $this->userModel->getUser($username);
            $this->response(['Status_Code'=>'226','Message'=>'Sucessfuly created','user'=>$new_user], REST_Controller::HTTP_IM_USED);
        }else{
            $this->response(['Status_Code' => '409','Message' => 'Username has been used'], REST_Controller::HTTP_CONFLICT);
        }
        
    }

}

?>