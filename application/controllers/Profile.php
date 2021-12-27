<?php defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        is_logged_in();
        //$this->load->model('Users_model');
        //$this->load->model('Log_model');
    }

    public function index() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $bidang = $this->Users_model->getBidang($this->session->userdata('username'));
        $sub_bidang = $this->Users_model->getSubBidang($this->session->userdata('username'));
        $data = [
            'title' => 'Profil User',
            'user' => $user,
            'bidang' => $bidang,
            'sub_bidang' => $sub_bidang
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        if ($user->role == 'Admin') { // role admin
            $this->load->view('template/sidebar_admin', $data);
            $this->load->view('admin/profile', $data);
        } elseif ($user->role == 'User') { // role user
            $this->load->view('template/sidebar_user', $data);
            $this->load->view('user/profile', $data);
        } elseif ($user->role == 'Super Admin') { // role superadmin
            $this->load->view('template/sidebar_superadmin', $data);
            $this->load->view('superadmin/profile', $data);
        }
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    private function _uploadImage() {
        $upload_image = $_FILES['foto']['name'];

        // cek ada file yg diupload atau tidak
        if ($upload_image) {
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 200;
            $config['upload_path'] = './assets/images/';
            // $config['overwrite'] = true;

            // load library upload
            $this->load->library('upload', $config);

            // cek apakah upload berhasil / gagal
            if ($this->upload->do_upload('file')) {
                // jika nama file bukan default.png, hapus
                if ($this->input->post('old_image') != 'default.png') {
                    unlink(FCPATH . 'assets/images/' . $this->input->post('old_image'));
                }
                return $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('failed', $this->upload->display_errors());
                redirect('profile');
            }
        } else {
            return $this->input->post('old_image');
        }
    }

    public function edit() {
        if ($this->input->post('password') == '') {
            $password = $this->input->post('old_password');
        } else {
            $password = htmlspecialchars(strip_tags($this->input->post('password')));
        }

        $data = [
            'nama' => htmlspecialchars(strip_tags($this->input->post('nama'))),
            'password' => $password,
            'foto' => $this->_uploadImage()
        ];
        $this->Users_model->editProfile($data, $this->session->userdata('username'));
        $this->session->set_flashdata('success', 'Profil berhasil diubah!');
        redirect('Profile');
    }

}
