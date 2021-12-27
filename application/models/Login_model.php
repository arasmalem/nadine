<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function getByUsername($username) {
       // return $this->db->get_where('user', ['username' => $username])->row();
    	$this->db->select('*');
        $this->db->from('user');
        $this->db->where('username',$username);
        return $this->db->get()->row();
	}

}
