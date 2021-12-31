<?php defined('BASEPATH') or exit('No direct script access allowed');

class SpaceSpt extends CI_Controller {

    private $_cname = 'spacespt';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('SpaceSpt_model');
        $this->load->model('Spt_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->SpaceSpt_model->getTotalRows();
        $config['base_url'] = base_url() . '/spacespt/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spacespt = $this->SpaceSpt_model->getAll($config['per_page'], $this->uri->segment(3));
        $bidang = $this->Spt_model->getBidang();

        $data = [
            'title' => 'Space Surat Perintah Tugas',
            'user' => $user,
            'bidang' => $bidang,
            'spacespt' => $spacespt,
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
        $this->load->view('admin/space_spt', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl_spt'] == '' and $post['nomor_agenda'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl_spt' => $post['tgl_spt'],
                    'nomor_agenda' => $post['nomor_agenda'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl_spt' => $this->session->userdata('tgl_spt'),
                'nomor_agenda' => $this->session->userdata('nomor_agenda'),
            ];
        }

        $total_rows = $this->SpaceSpt_model->getTotalRowsBySearch($data['tgl_spt'], $data['nomor_agenda']);
        $config['base_url'] = base_url() . '/spacespt/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spacespt = $this->SpaceSpt_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl_spt'], $data['nomor_agenda']);
        $bidang = $this->Spt_model->getBidang();

        $data = [
            'title' => 'Space Surat Perintah Tugas',
            'user' => $user,
            'spacespt' => $spacespt,
            'bidang' => $bidang,
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
        $this->load->view('admin/space_spt', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $no_hari_ini = $this->SpaceSpt_model->getNomorByCurDate(); // get jml nomor kosong per hari ini
        
        $sisa_no_kosong = 10 - $no_hari_ini;
        $post = $this->input->post();

        // masukkan data input dari user ke variabel
        $jml_space = $post['no_space_spt'];
        $tgl_spt = $post['tgl_spt'];

        // Konversi string ke date agar bisa dibandingkan
        $tgl_skrg = strtotime(date('Y-m-d'));
        $tgl_spt_konversi = strtotime($tgl_spt);

        /* cek kondisi dan looping insert ke database sebanyak data yang dimasukkan user */
        if ($tgl_spt == date('Y-m-d')) { // jika tgl surat = tgl hari ini
            if ($jml_space > 10) { // cek apakah jumlah space nomor yang diinput lebih dari 10
                $this->session->set_flashdata('failed', 'Jumlah nomor yang dimasukkan melebihi batas maksimal!');
                redirect($this->_cname);
            } elseif ($sisa_no_kosong == 0) { // jika sisa nomor kosong sudah habis pada hari ini
                $this->session->set_flashdata('failed', 'Anda sudah memasukkan 10 nomor kosong hari ini!');
                redirect($this->_cname);            
            } elseif ($jml_space > $sisa_no_kosong) { // jika nomor yg dimasukkan melebihi batas max. dari sisa nomor kosong di database
                $this->session->set_flashdata('failed', 'Sisa nomor kosong yang boleh dimasukkan hanya ' . $sisa_no_kosong . ' nomor hari ini!');
                redirect($this->_cname);
            } else {
                $no_by_tgl_spt = $this->SpaceSpt_model->getNomorByTglSurat($tgl_spt);
                $space_no_by_tgl_spt = $this->SpaceSpt_model->getSpaceNomorByTglSurat($tgl_spt);
                $no_max_by_tgl_sblm = $this->SpaceSpt_model->getTglMax();

                if (!empty($space_no_by_tgl_spt->nomor)) { // jika ada nomor pd tgl saat itu di tabel space_spt
                    $no_agenda = $space_no_by_tgl_spt->nomor;
                } elseif (empty($space_no_by_tgl_spt->nomor) and empty($no_by_tgl_spt->nomor)) { // jika tidak ada nomor pd tgl saat itu di tabel space_spt dan tabel surat_spt
                    $space_no_by_tgl_spt = $this->SpaceSpt_model->getSpaceNomorByTglSurat($no_max_by_tgl_sblm->tgl_spt);
                    $no_agenda = $space_no_by_tgl_spt->nomor;
                } else {
                    $no_agenda = $no_by_tgl_spt->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);

                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log(@$this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log(@$this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                }
            }
        } elseif ($tgl_spt_konversi < $tgl_skrg) { // jika tgl surat sebelum tgl hari ini
            $no_max = $this->SpaceSpt_model->getNomorMax();
            $no_by_tgl_spt = $this->SpaceSpt_model->getNomorByTglSurat($tgl_spt);
            $space_no_by_tgl_spt = $this->SpaceSpt_model->getSpaceNomorByTglSurat($tgl_spt);

            if (empty($no_by_tgl_spt->nomor) and empty($space_no_by_tgl_spt->nomor)) {
                $this->session->set_flashdata('failed', 'Cek kembali tanggal surat!');
                redirect($this->_cname);
                // pisahkan nomor berdasar titik
                $no_agenda = $no_max->nomor;
                $exp = explode('.', $no_agenda);
                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            } else {
                if (!empty($space_no_by_tgl_spt->nomor)) {
                    $no_agenda = $space_no_by_tgl_spt->nomor;
                } else {
                    $no_agenda = $no_by_tgl_spt->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);
                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda . '.' . $i;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                        $no_agenda = explode('.', $no_agenda);
                        $no_agenda = $no_agenda[0];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    $no_agenda2 = $exp[1];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda2 = $no_agenda2 + 1;
                        $no_agenda = $no_agenda . '.' . $no_agenda2;
                        $data = [
                            'tgl_spt' => $post['tgl_spt'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSpt_model->save($data);
                        $exp = explode('.', $no_agenda);
                        $no_agenda = $exp[0];
                        $no_agenda2 = $exp[1];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SPT');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            }                
        } elseif ($tgl_skrg < $tgl_spt_konversi) { // jika tgl surat melebihi tgl hari ini
            $this->session->set_flashdata('failed', 'Tanggal surat tidak boleh melebihi tanggal hari ini.');
            redirect($this->_cname);
        }
    }

    private function _uploadFile() {
        $method = $this->uri->segment(2);
        $upload_file = $_FILES['file']['name'];

        // jika methodnya tambah surat
        if ($method == 'used') {
            // cek ada file yg diupload atau tidak
            if ($upload_file) {
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = 1024;
                $config['upload_path'] = './files_spt';
                $config['file_name'] = 'spt-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
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
        }
    }

    public function used() {
        $spt_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_spt = '094/' . $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $post['nomor_agenda'],
	        'nomor_spt' => $nomor_spt,
            'tgl_spt' => $post['tgl_spt'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'created_at' => date('Y-m-d H:i:s'),
	        'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke tabel surat_spt dan hapus record di tabel space_spt
        $this->Spt_model->save($data);
        $this->SpaceSpt_model->delete($spt_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan SPT dengan nomor ' . $nomor_spt);
        
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect('SPT');
    }

    public function delete() {
        // get id pada url
        $spt_id = $this->uri->segment(3);

        // jalankan fungsi delete
        $this->SpaceSpt_model->delete($spt_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus space SPT');

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('tgl_spt');
        $this->session->unset_userdata('nomor_agenda');
        redirect($this->_cname);
    }

}
