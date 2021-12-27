<?php defined('BASEPATH') or exit('No direct script access allowed');

class UnitKerja extends CI_Controller {

    private $_cname = 'UnitKerja';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('UnitKerja_model');
        //$this->load->model('Users_model');
        //$this->load->model('Log_model');
    }

    public function index() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $data = [
            'title' => 'Unit Kerja',
            'user' => $user
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/unit_kerja');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function unit() {
		$unit = $this->UnitKerja_model->getUnit();
		$arr = [];
        foreach ($unit as $value) :
            $url = site_url('UnitKerja/editunitkerja/') . $value->id;
            $array = [
                'id' => $value->id,
                'pId' => $value->pId,
                'name' => $value->id . ' - ' . $value->name,
                'url' => $url,
                'target' => '_blank',
                'icon' => base_url() . 'assets/zTree/css/zTreeStyle/img/diy/1_close.png',
                'open' => $value->open
            ];
            array_push($arr, $array);
        endforeach;
        echo json_encode($arr);
    }

    public function addUnitKerja() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $unit = $this->UnitKerja_model->getUnit();
        $data = [
            'title' => 'Tambah Unit Kerja',
            'user' => $user,
            'unit' => $unit
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/unit_kerja_add', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }
    
    public function editUnitKerja($id) {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $unit = $this->UnitKerja_model->getUnitById($id);
        $data = [
            'title' => 'Edit Unit Kerja',
            'user' => $user,
            'unit' => $unit
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/unit_kerja_edit', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();
        $data = [
            'id' => htmlspecialchars($post['id']),
            'pId' => htmlspecialchars($post['pid']),
            'name' => htmlspecialchars($post['nama'])
        ];

        // insert into database
        $this->UnitKerja_model->save($data);

        // insert into log_activity table
        activity_log(@$this->uri->segment(1), 'insert', 'menambahkan unit kerja');

        $this->session->set_flashdata('success', 'Unit kerja berhasil ditambah!');
        redirect($this->_cname);
    }

    public function edit() {
        $id = $this->uri->segment(3);
        $data['name'] = $this->input->post('nama');

        // update tabel skpd
        $this->UnitKerja_model->update($data, $id);

        // insert into log_activity table
        activity_log(@$this->uri->segment(1), 'update', 'mengubah unit kerja');

        $this->session->set_flashdata('success', 'Unit kerja berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        $id = $this->uri->segment(3);

        /* insert into log_activity table */
        activity_log(@$this->uri->segment(1), 'delete', 'menghapus unit kerja');

        /* hapus data berdasar id */
        $this->UnitKerja_model->delete($id);

        $this->session->set_flashdata('success', 'Unit kerja berhasil dihapus!');
        redirect($this->_cname);
    }
    
}
