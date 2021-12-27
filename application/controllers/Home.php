<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('Home_model');
    }

    public function index() {
        if ($this->session->userdata('role') == 'Admin') {
            $user = $this->Users_model->getByUsername($this->session->userdata('username'));
            $jml_srt_keluar = $this->Home_model->getJmlSuratKeluar();
            $jml_spt = $this->Home_model->getJmlSpt();
            $jml_sk = $this->Home_model->getJmlSk();
            $data = [
                'title' => 'Admin Page',
                'user' => $user,
                'jmlSrtKeluar' => $jml_srt_keluar,
                'jmlSk' => $jml_sk,
                'jmlSpt' => $jml_spt,
            ];
            $this->load->view('template/header', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('template/sidebar_admin', $data);
            $this->load->view('admin/index');
            $this->load->view('template/footer_home');
            $this->load->view('modal-logout');

        } elseif ($this->session->userdata('role') == 'Super Admin') {
            $user = $this->Users_model->getByUsername($this->session->userdata('username'));
            $jml_srt_keluar = $this->Home_model->getJmlSuratKeluar();
            $jml_spt = $this->Home_model->getJmlSpt();
            $jml_sk = $this->Home_model->getJmlSk();
            $jml_user = $this->Home_model->getJmlUser();
            $latest_log = $this->Home_model->getLatestActivity(7);

            $data = [
                'title' => 'Super Admin Page',
                'user' => $user,
                'jmlSrtKeluar' => $jml_srt_keluar,
                'jmlSk' => $jml_sk,
                'jmlSpt' => $jml_spt,
                'jmlUser' => $jml_user,
                'latest_log' => $latest_log
            ];
            $this->load->view('template/header', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('template/sidebar_superadmin', $data);
            $this->load->view('superadmin/index');
            $this->load->view('template/footer_home');
            $this->load->view('modal-logout');

        } elseif ($this->session->userdata('role') == 'User') {
            $user = $this->Users_model->getByUsername($this->session->userdata('username'));
            $data = [
                'title' => 'User Page',
                'user' => $user
            ];
            $this->load->view('template/header', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('template/sidebar_user', $data);
            $this->load->view('user/index');
            $this->load->view('template/footer');
            $this->load->view('modal-logout');
        }elseif ($this->session->userdata('role') == 'UserPersonal') {
            $user = $this->Users_model->getByUsername($this->session->userdata('username'));
            $data = [
                'title' => 'User Page',
                'user' => $user
            ];
            $this->load->view('template/header', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('template/sidebar_user', $data);
            $this->load->view('user/index');
            $this->load->view('template/footer');
            $this->load->view('modal-logout');
        }
    }

}
