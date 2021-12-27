<?php defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiSurat extends CI_Controller {

    private $_cname = 'klasifikasisurat';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('KlasifikasiSurat_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->KlasifikasiSurat_model->getTotalRows();
        $config['base_url'] = base_url() . '/klasifikasisurat/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION AND SEARCH BOX */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $klasifikasi = $this->KlasifikasiSurat_model->getAll($config['per_page'], $this->uri->segment(3));

        $data = [
            'title' => 'Klasifikasi Surat',
            'user' => $user,
            'klasifikasi' => $klasifikasi,
            'total_rows' => $config['total_rows'],
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3)
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/klasifikasi_surat');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['kode_surat'] == '' and $post['klasifikasi'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'kode_surat' => $post['kode_surat'],
                    'klasifikasi' => $post['klasifikasi'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'kode_surat' => $this->session->userdata('kode_surat'),
                'klasifikasi' => $this->session->userdata('klasifikasi'),
            ];
        }

        $total_rows = $this->KlasifikasiSurat_model->getTotalRowsBySearch($data['kode_surat'], $data['klasifikasi']);
        $config['base_url'] = base_url() . '/klasifikasisurat/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 1;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $klasifikasi = $this->KlasifikasiSurat_model->getAll($config['per_page'], $this->uri->segment(3), $data['kode_surat'], $data['klasifikasi']);
        $data = [
            'title' => 'Klasifikasi Surat',
            'user' => $user,
            'klasifikasi' => $klasifikasi,
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3),
            'total_rows' => $config['total_rows']
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
        $this->load->view('superadmin/klasifikasi_surat', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $data['kode_surat'] = htmlspecialchars($this->input->post('kode'), true);
        $data['klasifikasi'] = htmlspecialchars($this->input->post('klasifikasi'), true);

        // insert into database
        $this->KlasifikasiSurat_model->save($data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan klasifikasi surat');

        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect($this->_cname);
    }

    public function edit() {
        $id = $this->uri->segment(3);
        $data['kode_surat'] = htmlspecialchars($this->input->post('kode'), true);
        $data['klasifikasi'] = htmlspecialchars($this->input->post('klasifikasi'), true);

        // update tabel klasifikasi_surat
        $this->KlasifikasiSurat_model->update($id, $data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah klasifikasi surat');

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        $id = $this->uri->segment(3);

        /* insert into log_activity table */
        activity_log($this->uri->segment(1), 'delete', 'menghapus klasifikasi surat');

        /* hapus data berdasar id */
        $this->KlasifikasiSurat_model->delete($id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname); 
    }

    public function reset() {
        $this->session->unset_userdata('keyword');
        redirect($this->_cname);
    }

}
