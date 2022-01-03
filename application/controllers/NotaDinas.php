<?php defined('BASEPATH') or exit('No direct script access allowed');

class NotaDinas extends CI_Controller {

    private $_cname = 'notadinas';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('NotaDinas_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->NotaDinas_model->getTotalRows();
        $config['base_url'] = base_url() . '/notadinas/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $nota_dinas = $this->NotaDinas_model->getAll($config['per_page'], $this->uri->segment(3), null, null, null, $user->username, $user->sub_bidang);
        $klasifikasi = $this->NotaDinas_model->getKlasifikasi();
        $sifat = $this->NotaDinas_model->getSifat();
        $sub_bidang = $this->NotaDinas_model->getSubBidang();

        $data = [
            'title' => 'Nota Dinas',
            'user' => $user,
            'nota_dinas' => $nota_dinas,
            'klasifikasi' => $klasifikasi,
            'sifat' => $sifat,
            'sub_bidang' => $sub_bidang,
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
        }elseif ($this->session->userdata('role') == 'User') {
            $this->load->view('template/sidebar_user', $data);
        }
        $this->load->view('admin/nota_dinas', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl_notdin'] == '' and $post['nomor_notdin'] == '' and $post['perihal'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl_notdin' => $post['tgl_notdin'],
                    'nomor_notdin' => $post['nomor_notdin'],
                    'perihal' => $post['perihal'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl_notdin' => $this->session->userdata('tgl_notdin'),
                'nomor_notdin' => $this->session->userdata('nomor_notdin'),
                'perihal' => $this->session->userdata('perihal'),
            ];
        }

        $total_rows = $this->NotaDinas_model->getTotalRowsBySearch($data['nomor_notdin'], $data['perihal'], $data['tgl_notdin']);
        $config['base_url'] = base_url() . '/notadinas/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 1;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $nota_dinas = $this->NotaDinas_model->getAll($config['per_page'], $this->uri->segment(3), $data['nomor_notdin'], $data['perihal'], $data['tgl_notdin'], $user->username, $user->sub_bidang);
        $klasifikasi = $this->NotaDinas_model->getKlasifikasi();
        $sifat = $this->NotaDinas_model->getSifat();
        $sub_bidang = $this->NotaDinas_model->getSubBidang();
        $data = [
            'title' => 'Nota Dinas',
            'user' => $user,
            'nota_dinas' => $nota_dinas,
            'sub_bidang' => $sub_bidang,
            'klasifikasi' => $klasifikasi,
            'sifat' => $sifat,
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
        $this->load->view('admin/nota_dinas', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();
	    $no_agenda = $this->NotaDinas_model->getNomorAgenda(date('Y'));

	    if (date('d') == '02' and date('m') == '01') {
            if (empty($no_agenda->nomor)) {
                $no_agenda = 1;
            } else {
                $no_agenda = $no_agenda->nomor + 1;
            }      
        } else {
            $no_agenda = $no_agenda->nomor + 1;
        }

        $nomor_notdin = $post['klasifikasi'] . '/' . $no_agenda . '/' . $post['sub_bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $no_agenda,
            'tujuan' => htmlspecialchars(strtoupper($post['tujuan']), true),
            'nomor_notdin' => $nomor_notdin,
            'tgl_notdin' => $post['tgl_notdin'],
            'sifat_id' => $post['sifat'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'klasifikasi' => $post['klasifikasi'],
            'sub_bidang' => $this->session->userdata('sub_bidang'),
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke database
        $this->NotaDinas_model->save($data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan nota dinas dengan nomor ' . $nomor_notdin);

        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect($this->_cname);        
    }

    private function _uploadFile() {
        $method = $this->uri->segment(2);
        $upload_file = $_FILES['file']['name'];

        // jika methodnya tambah surat
        if ($method == 'add') {
            // cek ada file yg diupload atau tidak
            if ($upload_file) {
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = 1024;
                $config['upload_path'] = './files_nota_dinas';
                $config['file_name'] = 'nota_dinas-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
                // $config['overwrite'] = true;

                // load library upload
                $this->load->library('upload', $config);

                // cek apakah upload berhasil / gagal
                if ($this->upload->do_upload('file')) {
                    return $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('failed', $this->upload->display_errors());
                    redirect($this->_cname);
                }
            } else {
               $this->session->set_flashdata('failed', 'File surat harus diupload!');
               redirect($this->_cname);
            }

        // jika methodnya edit surat
        } elseif ($method == 'edit') {
            // cek ada file yg diupload atau tidak
            if ($upload_file) {
                    $config['allowed_types'] = 'pdf';
                    $config['max_size'] = 1024;
                    $config['upload_path'] = './files_nota_dinas';
                    $config['file_name'] = 'nota_dinas-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
                    // $config['overwrite'] = true;

                    // load library upload
                    $this->load->library('upload', $config);

                    // cek apakah upload berhasil / gagal
                    if ($this->upload->do_upload('file')) {
                        unlink(FCPATH . './files_nota_dinas/' . $this->input->post('old_file'));
                        return $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('failed', $this->upload->display_errors());
                        redirect($this->_cname);
                    }
            } else {
                return $this->input->post('old_file');
            }
        }
    }

    public function edit() {
        $surat_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_notdin = $post['klasifikasi'] . '/' . $post['nomor_agenda'] . '/' . $post['sub_bidang'] . '/' . date('Y');
        $data = [
            'tujuan' => htmlspecialchars(strtoupper($post['tujuan']), true),
            'nomor_notdin' => $nomor_notdin,
            'tgl_notdin' => $post['tgl_notdin'],
            'sifat_id' => $post['sifat'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'klasifikasi' => $post['klasifikasi'],
            'operator' => $this->session->userdata('username'),
            'file_surat' => $this->_uploadFile(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // update ke database
        $this->NotaDinas_model->update($data, $surat_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah nota dinas dengan nomor ' . $nomor_notdin);

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        // get id pada url
        $surat_id = $this->uri->segment(3);

        // get nama file di database
        $row = $this->NotaDinas_model->getById($surat_id);
        
        // hapus file di folder
        unlink(FCPATH . './files_nota_dinas/' . $row->file_surat);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus nota dinas dengan nomor ' . $row->nomor_notdin);

        // jalankan fungsi delete
        $this->NotaDinas_model->delete($surat_id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('nomor_notdin');
        $this->session->unset_userdata('perihal');
        $this->session->unset_userdata('tgl_notdin');
        redirect($this->_cname);
    }

}
