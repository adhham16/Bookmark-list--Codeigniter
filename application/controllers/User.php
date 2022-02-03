<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class USER extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
  }

  public function index()
  {
    $this->load->view('user/login');
  }
  public function login(){
      $this->load->view('user/login');
  }
  public function user_main(){
    $base_url = "http://localhost:8012/bookmark_list/bookmark_list/index.php";
      $username = $this->input->post('login_username');
      $password = $this->input->post('login_password');
      if (isset($username) && isset($password)){
        $data = ['ad'];
        $url = $base_url.'/api/User/userValidation';
        $url2 = $base_url.'/api/Bookmarks/getBookmarks';
        $payload = [
          "username"=> $username,
          "password" => $password
        ];
        // $url = 'http://localhost:8012/bookmark_list/bookmark_list/index.php/api/User/userValidation'
        $userdata = json_decode($this->do_curl($url,"POST",[],$payload),true);
        $payload2 = [
          "id" => $userdata['user'][0]['id']
        ];
        $bookmark_data = json_decode($this->do_curl($url2,"POST",[],$payload2),TRUE);
        $data['bookmarks']=$bookmark_data['bookmarks'];
        $session = $userdata['user'][0];
        $this->session->set_userdata($session); 
        $this->load->view('user/user_view',array('data'=> $data));
        // print_r($userdata);
      }else{
        $this->load->view('errors/html/not_found');
      }
      
      
  }
  public function logout(){
   
    $this->session->unset_userdata('user');  
    print_r($_SESSION);
    redirect("http://localhost:8012/bookmark_list/bookmark_list/index.php/user/login");  
  }

// curl function
  function do_curl($url, $type = "GET", $headers=[], $payload='',$redirectURL = false)
  {
        $ch = curl_init();
		    $cookiefile = dirname(__FILE__)."/cookies_oxford.txt" ; 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEJAR,$cookiefile);
		    curl_setopt($ch, CURLOPT_COOKIEFILE,$cookiefile);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === FALSE) {
            printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
                htmlspecialchars(curl_error($ch)));
        }

        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        curl_close($ch);
        if($redirectURL){
            return $info['redirect_url'];
        }else{
            return $response;
        }
    }
}