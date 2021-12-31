<?php defined('BASEPATH') or exit('No direct script access allowed');

class SpaceKeluar extends CI_Controller {

    private $_cname = 'spacekeluar';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('SpaceKeluar_model');
        $this->load->model('SuratKeluar_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->SpaceKeluar_model->getTotalRows();
        $config['base_url'] = base_url() . '/spacekeluar/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spaceKeluar = $this->SpaceKeluar_model->getAll($config['per_page'], $this->uri->segment(3));
        $klasifikasi = $this->SuratKeluar_model->getKlasifikasi();
        $sifat = $this->SuratKeluar_model->getSifat();
        $bidang = $this->SuratKeluar_model->getBidang();

        $data = [
            'title' => 'Space Surat Keluar',
            'user' => $user,
            'spaceKeluar' => $spaceKeluar,
            'sifat' => $sifat,
            'bidang' => $bidang,
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
        $this->load->view('admin/space_keluar', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl'] == '' and $post['nomor_agenda'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl' => $post['tgl'],
                    'nomor_agenda' => $post['nomor_agenda'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl' => $this->session->userdata('tgl'),
                'nomor_agenda' => $this->session->userdata('nomor_agenda'),
            ];
        }

        $total_rows = $this->SpaceKeluar_model->getTotalRowsBySearch($data['tgl'], $data['nomor_agenda']);
        $config['base_url'] = base_url() . '/spacekeluar/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spaceKeluar = $this->SpaceKeluar_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl'], $data['nomor_agenda']);
        $klasifikasi = $this->SuratKeluar_model->getKlasifikasi();
        $sifat = $this->SuratKeluar_model->getSifat();
        $bidang = $this->SuratKeluar_model->getBidang();

        $data = [
            'title' => 'Space Surat Keluar',
            'user' => $user,
            'spaceKeluar' => $spaceKeluar,
            'sifat' => $sifat,
            'bidang' => $bidang,
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
        $this->load->view('admin/space_keluar', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();

        $no_hari_ini = $this->SpaceKeluar_model->getNomorByCurDate(); // get jml nomor kosong per hari ini
        $sisa_no_kosong = 10 - $no_hari_ini;

        // masukkan data input dari user ke variabel
        $jml_space = $post['no_space_keluar'];
        $tgl_surat = $post['tgl_surat'];

        // Konversi string ke date agar bisa dibandingkan
        $tgl_skrg = strtotime(date('Y-m-d'));
        $tgl_surat_konversi = strtotime($tgl_surat);

        /* cek kondisi dan looping insert ke database sebanyak data yang dimasukkan user */
        if ($tgl_surat == date('Y-m-d')) { // jika tgl surat == tgl hari ini
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
                $no_by_tgl_surat = $this->SpaceKeluar_model->getNomorByTglSurat($tgl_surat);
                $space_no_by_tgl_surat = $this->SpaceKeluar_model->getSpaceNomorByTglSurat($tgl_surat);
                $no_max_by_tgl_sblm = $this->SpaceKeluar_model->getTglMax();

                if (!empty($space_no_by_tgl_surat->nomor)) { // jika ada nomor pd tgl saat itu di tabel space_keluar 
                    $no_agenda = $space_no_by_tgl_surat->nomor;
                } elseif (empty($space_no_by_tgl_surat->nomor) and empty($no_by_tgl_surat->nomor)) { // jika tidak ada nomor pd tgl saat itu di tabel space_keluar dan tabel surat_keluar
                    $space_no_by_tgl_surat = $this->SpaceKeluar_model->getSpaceNomorByTglSurat($no_max_by_tgl_sblm->tgl);
                    $no_agenda = $space_no_by_tgl_surat->nomor;
                } else {
                    $no_agenda = $no_by_tgl_surat->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);

                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                }
            }
        } elseif ($tgl_surat_konversi < $tgl_skrg) { // jika tgl surat sebelum tgl hari ini
            $no_max = $this->SpaceKeluar_model->getNomorMax();
            $no_by_tgl_surat = $this->SpaceKeluar_model->getNomorByTglSurat($tgl_surat);
            $space_no_by_tgl_surat = $this->SpaceKeluar_model->getSpaceNomorByTglSurat($tgl_surat);

            if (empty($no_by_tgl_surat->nomor) and empty($space_no_by_tgl_surat->nomor)) {
                $this->session->set_flashdata('failed', 'Cek kembali tanggal surat!');
                redirect($this->_cname);
                // pisahkan nomor berdasar titik
                $no_agenda = $no_max->nomor;
                $exp = explode('.', $no_agenda);

                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            } else {
                if (!empty($space_no_by_tgl_surat->nomor)) {
                    $no_agenda = $space_no_by_tgl_surat->nomor;
                } else {
                    $no_agenda = $no_by_tgl_surat->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);

                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda . '.' . $i;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                        $no_agenda = explode('.', $no_agenda);
                        $no_agenda = $no_agenda[0];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    $no_agenda2 = $exp[1];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda2 = $no_agenda2 + 1;
                        $no_agenda = $no_agenda . '.' . $no_agenda2;
                        $data = [
                            'tgl' => $post['tgl_surat'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceKeluar_model->save($data);
                        $exp = explode('.', $no_agenda);
                        $no_agenda = $exp[0];
                        $no_agenda2 = $exp[1];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space keluar');
                    
                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            }
        } elseif ($tgl_skrg < $tgl_surat_konversi) { // jika tgl surat melebihi tgl hari ini
            $this->session->set_flashdata('failed', 'Tanggal surat tidak boleh melebihi tanggal hari ini!');
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
            $config['upload_path'] = './files_surat_keluar';
            $config['file_name'] = 'surat_keluar-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
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
        $surat_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_surat_keluar = $post['klasifikasi'] . '/' . $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'tujuan' => htmlspecialchars(strtoupper($post['tujuan']), true),
            'nomor_agenda' => $post['nomor_agenda'],
            'nomor_surat_keluar' => $nomor_surat_keluar,
            'tgl' => $post['tgl'],
            'sifat_id' => $post['sifat'],
            'lampiran' => $post['lampiran'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'ringkasan' => htmlspecialchars($post['ringkasan'], true),
            'klasifikasi' => $post['klasifikasi'],
            'operator' => 'frontdesk',
            'file_surat' => $this->_uploadFile(),
            'created_at' => date('Y-m-d H:i:s'),
	        'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke tabel surat_keluar dan hapus record di tabel space_keluar
        $this->SuratKeluar_model->save($data);
        $this->SpaceKeluar_model->delete($surat_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan surat keluar dengan nomor ' . $nomor_surat_keluar);

        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect('suratkeluar');
    }

    public function delete() {
        // get id pada url
        $surat_id = $this->uri->segment(3);

        // jalankan fungsi delete
        $this->SpaceKeluar_model->delete($surat_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus space surat keluar');

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('tgl');
        $this->session->unset_userdata('nomor_agenda');
        redirect($this->_cname);
    }

}
