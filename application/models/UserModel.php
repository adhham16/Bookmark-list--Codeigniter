<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class userModel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();

	}

	public function allUser(){
		$q = $this->db->get('users');
		return ($q->result_array());
	}
	public function getUser($username){
		$query = $this->db->get_where('users', array('userName' => $username));
		return $query->row_array();
	}
    public function addUser($data){
		$this->db->insert('users', $data);
	}
	
}
?>
