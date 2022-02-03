<?php 


use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
     
class Bookmarks extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('bookmarksModel');
     }

    public function getBookmarks_post(){
        $user_id = $this->input->post('id');
        $bookmark_data =  $this->bookmarksModel->getBookmarks($user_id);
        $this->response(['status-code'=>'200','bookmarks'=>$bookmark_data],REST_Controller::HTTP_OK);
    }
    public function getBookmark_post(){
        $id = $this->input->post('id');
        $bookmark_data =  $this->bookmarksModel->getBookmark($id);
        $this->response(['status-code'=>'200','bookmarks'=>$bookmark_data],REST_Controller::HTTP_OK);
    }

    public function addBookmarks_put(){
        $data = $this->put();
        $this->bookmarksModel->addBookmarks($data);
        $this->response(['status-code'=>'200','message'=> 'Successfully added'],REST_Controller::HTTP_OK);
    }
    public function updateBookmarks_post(){
        $name = $this->input->post('name');
        $url = $this->input->post('url');
        $tags = $this->input->post('tags');
        $user_id = $this->input->post('user_id');
        $note = $this->input->post('note');
        $id = $this->input->post('id');
        $data = [
            'bookmark_name' => $name,
            'bookmark_url' => $url,
            'user_id' =>  $user_id,
            'bookmark_tags' => $tags,
            'bookmark_note' => $note
        ];
        $this->bookmarksModel->updateBookmark($id,$data);
        $this->response(['status-code'=>'200','message'=> 'Successfully updated'],REST_Controller::HTTP_OK);
    }

    public function deleteBookmark_post(){
        $id =  $this->input->post('id');
        $this->bookmarksModel->deleteBookmark($id);
        $this->response(['status-code'=>'200','message'=> 'Successfully deleted'],REST_Controller::HTTP_OK);
    }


}


?>