<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bookmarksModel extends CI_Model{

    public function __construct()
	{
		$this->load->database();

	}

    public function allBookmarks(){
		$q = $this->db->get('bookmark_list');
		return ($q->result_array());
	}
    public function getBookmark($id){
		$query = $this->db->get_where('bookmark_list', array('id' => $id));
		return ($query->result_array());
	}
	public function getBookmarks($user_id){
		$query = $this->db->get_where('bookmark_list', array('user_id' => $user_id));
		return ($query->result_array());
	}
    public function addBookmarks($data){
		$this->db->insert('bookmark_list', $data);
	}

    public function updateBookmark($id,$data){
        $this->db->where('id', $id);
        $this->db->update('bookmark_list', $data);
    }

    public function deleteBookmark($id){
        $this->db->delete('bookmark_list', array('id' => $id));
    }
    
}
?>