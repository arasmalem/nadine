<?php defined('BASEPATH') or exit('No direct script access allowed');

class SifatSurat extends CI_Controller {

    private $_cname = 'SifatSurat';

    public function __construct() {
          parent::__construct();
          is_logged_in();
          //$this->load->model('Users_model');
          $this->load->model('SifatSurat_model');
          //$this->load->model('Log_model');
      }

    public function index() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $sifat = $this->SifatSurat_model->getAll();

        $data = [
            'title' => 'Sifat Surat',
            'user' => $user,
            'sifat' => $sifat
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/sifat_surat');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $data['sifat'] = htmlspecialchars($this->input->post('sifat'), true);

        // insert into database
        $this->SifatSurat_model->save($data);

        // insert into log_activity table
        activity_log(@$this->uri->segment(1), 'insert', 'menambahkan sifat surat');

        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect($this->_cname);
    }

    public function edit() {
        $id = $this->uri->segment(3);
        $data['sifat'] = htmlspecialchars($this->input->post('sifat'), true);

        // update tabel sifat_surat
        $this->SifatSurat_model->update($id, $data);

        // insert into log_activity table
        activity_log(@$this->uri->segment(1), 'update', 'mengubah sifat surat');

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        $id = $this->uri->segment(3);

        /* insert into log_activity table */
        activity_log(@$this->uri->segment(1), 'delete', 'menghapus sifat surat');

        /* hapus data berdasar id */
        $this->SifatSurat_model->delete($id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname); 
    }

}
