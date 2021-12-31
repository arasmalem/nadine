<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
    }
    
    public function index() {
        // validasi input user
        $this->form_validation->set_rules('username', 'Username', 'trim|required', ['required' => 'username tidak boleh kosong!']);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', ['required' => 'password tidak boleh kosong!']);

        if ($this->form_validation->run() == FALSE) { // jika validasi gagal redirect ke halaman login
            $data['title'] = 'Login Page';
            $this->load->view('auth/login', $data);
        } else { // jika validasi berhasil jalankan function login
            $this->_login();
        }
    }

    private function _login() {
        $post = $this->input->post();

        // cek input dari user
        $username = $post['username'];
        $password = $post['password'];

        // cari user di database
        $user = $this->Login_model->getByUsername($username);
        
        if ($user) { // jika user ditemukan
            // cek status user
            if ($user->status == 'Active') {
                // cek password
                if (!password_verify($password, $user->password)) {
                    $this->session->set_flashdata('failed', 'login gagal!');
                    redirect('auth');
                } else {
                    $data = [
                        'username' => $user->username,
                        'nama' => $user->nama,
                        'role' => $user->role,
                        'bidang' => $user->bidang,
                        'sub_bidang' => $user->sub_bidang
                    ];
                    // masukkan data user ke dalam session
                    $this->session->set_userdata($data);
                    // redirect ke halaman home
                    redirect('home');
                }
            } else { // jika user tidak aktif / banned
                $this->session->set_flashdata('failed', 'Maaf, user anda telah diblokir!');
                redirect('auth');
            }
        } else { // jika user tidak ditemukan
            $this->session->set_flashdata('failed', 'login gagal!');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('nama');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('bidang');
        $this->session->unset_userdata('sub_bidang');
        $this->session->unset_userdata('keyword');
        // $this->session->set_flashdata('success', 'Anda telah logout dari aplikasi.');
        redirect('auth');
    }

    public function blocked() {
        $this->load->view('auth/blocked');
    }

}
