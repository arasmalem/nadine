<?php defined('BASEPATH') or exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
    }

    public function index() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $data = [
            'title' => 'Chat Room',
            'user' => $user,
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        if ($this->session->userdata('role') == 'Super Admin') {
            $this->load->view('template/sidebar_superadmin', $data);
        } elseif ($this->session->userdata('role') == 'Admin') {
            $this->load->view('template/sidebar_admin', $data);
        } elseif ($this->session->userdata('role') == 'User') {
            $this->load->view('template/sidebar_user', $data);
        }
        $this->load->view('user/chat', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

}
