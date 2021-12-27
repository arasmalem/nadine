<?php defined('BASEPATH') or exit('No direct script access allowed');

class SpaceSk extends CI_Controller {

    private $_cname = 'spacesk';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('SpaceSk_model');
        $this->load->model('Sk_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->SpaceSk_model->getTotalRows();
        $config['base_url'] = base_url() . '/spacesk/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spacesk = $this->SpaceSk_model->getAll($config['per_page'], $this->uri->segment(3));
        $bidang = $this->SpaceSk_model->getBidang();
        $klasifikasi = $this->Sk_model->getKlasifikasi();
        $data = [
            'title' => 'Space SK',
            'user' => $user,
            'bidang' => $bidang,
            'klasifikasi' => $klasifikasi,
            'spacesk' => $spacesk,
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
        $this->load->view('admin/space_sk', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl_sk'] == '' and $post['nomor_agenda'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl_sk' => $post['tgl_sk'],
                    'nomor_agenda' => $post['nomor_agenda'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl_sk' => $this->session->userdata('tgl_sk'),
                'nomor_agenda' => $this->session->userdata('nomor_agenda'),
            ];
        }

        $total_rows = $this->SpaceSk_model->getTotalRowsBySearch($data['tgl_sk'], $data['nomor_agenda']);
        $config['base_url'] = base_url() . '/spacesk/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 1;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spacesk = $this->SpaceSk_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl_sk'], $data['nomor_agenda']);
        $bidang = $this->SpaceSk_model->getBidang();
        $data = [
            'title' => 'Space SK',
            'user' => $user,
            'spacesk' => $spacesk,
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
        $this->load->view('admin/space_sk', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $no_hari_ini = $this->SpaceSk_model->getNomorByCurDate(); // get jml nomor kosong per hari ini
        
        $sisa_no_kosong = 10 - $no_hari_ini;
        $post = $this->input->post();

        // masukkan data input dari user ke variabel
        $jml_space = $post['no_space_sk'];
        $tgl_sk = $post['tgl_sk'];

        // Konversi string ke date agar bisa dibandingkan
        $tgl_skrg = strtotime(date('Y-m-d'));
        $tgl_sk_konversi = strtotime($tgl_sk);

        /* cek kondisi dan looping insert ke database sebanyak data yang dimasukkan user */
        if ($tgl_sk == date('Y-m-d')) { // jika tgl surat = tgl hari ini
            if ($jml_space > 5) { // cek apakah jumlah space nomor yang diinput lebih dari 5
                $this->session->set_flashdata('failed', 'Jumlah nomor yang dimasukkan melebihi batas maksimal!');
                redirect($this->_cname);
            } elseif ($sisa_no_kosong == 0) { // jika sisa nomor kosong sudah habis pada hari ini
                $this->session->set_flashdata('failed', 'Anda sudah memasukkan 5 nomor kosong hari ini!');
                redirect($this->_cname);            
            } elseif ($jml_space > $sisa_no_kosong) { // jika nomor yg dimasukkan melebihi batas max. dari sisa nomor kosong di database
                $this->session->set_flashdata('failed', 'Sisa nomor kosong yang boleh dimasukkan hanya ' . $sisa_no_kosong . ' nomor hari ini!');
                redirect($this->_cname);
            } else {
                $no_by_tgl_sk = $this->SpaceSk_model->getNomorByTglSurat($tgl_sk);
                $space_no_by_tgl_sk = $this->SpaceSk_model->getSpaceNomorByTglSurat($tgl_sk);
                $no_max_by_tgl_sblm = $this->SpaceSk_model->getTglMax();

                if (!empty($space_no_by_tgl_sk->nomor)) { // jika ada nomor pd tgl saat itu di tabel space_sk
                    $no_agenda = $space_no_by_tgl_sk->nomor;
                } elseif (empty($space_no_by_tgl_sk->nomor) and empty($no_by_tgl_sk->nomor)) { // jika tidak ada nomor pd tgl saat itu di tabel space_sk dan tabel surat_sk
                    $space_no_by_tgl_sk = $this->SpaceSk_model->getSpaceNomorByTglSurat($no_max_by_tgl_sblm->tgl_sk);
                    $no_agenda = $space_no_by_tgl_sk->nomor;
                } else {
                    $no_agenda = $no_by_tgl_sk->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);

                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log(@$this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log(@$this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname); 
                }
            }
        } elseif ($tgl_sk_konversi < $tgl_skrg) { // jika tgl surat sebelum tgl hari ini
            $no_max = $this->SpaceSk_model->getNomorMax();
            $no_by_tgl_sk = $this->SpaceSk_model->getNomorByTglSurat($tgl_sk);
            $space_no_by_tgl_sk = $this->SpaceSk_model->getSpaceNomorByTglSurat($tgl_sk);

            if (empty($no_by_tgl_sk->nomor) and empty($space_no_by_tgl_sk->nomor)) {
                $this->session->set_flashdata('failed', 'Cek kembali tanggal surat!');
                redirect($this->_cname);
                // pisahkan nomor berdasar titik
                $no_agenda = $no_max->nomor;
                $exp = explode('.', $no_agenda);
                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda + 1;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            } else {
                if (!empty($space_no_by_tgl_sk->nomor)) {
                    $no_agenda = $space_no_by_tgl_sk->nomor;
                } else {
                    $no_agenda = $no_by_tgl_sk->nomor;
                }
                // pisahkan nomor berdasar titik
                $exp = explode('.', $no_agenda);
                if (empty($exp[1])) { // jika tidak ada titik
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda = $no_agenda . '.' . $i;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                        $no_agenda = explode('.', $no_agenda);
                        $no_agenda = $no_agenda[0];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                } else {
                    $no_agenda = $exp[0];
                    $no_agenda2 = $exp[1];
                    for ($i = 1; $i <= $jml_space; $i++) {
                        $no_agenda2 = $no_agenda2 + 1;
                        $no_agenda = $no_agenda . '.' . $no_agenda2;
                        $data = [
                            'tgl_sk' => $post['tgl_sk'],
                            'nomor_agenda' => $no_agenda,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        // insert ke database
                        $this->SpaceSk_model->save($data);
                        $exp = explode('.', $no_agenda);
                        $no_agenda = $exp[0];
                        $no_agenda2 = $exp[1];
                    }
                    // insert into log_activity table
                    activity_log($this->uri->segment(1), 'insert', 'menambahkan space SK');

                    $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
                    redirect($this->_cname);
                }
            }                
        } elseif ($tgl_skrg < $tgl_sk_konversi) { // jika tgl surat melebihi tgl hari ini
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
                $config['upload_path'] = './files_sk';
                $config['file_name'] = 'sk-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
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
        $sk_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_sk = $post['klasifikasi'] . '/' . $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $post['nomor_agenda'],
            'nomor_sk' => $nomor_sk,
            'tgl_sk' => $post['tgl_sk'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'klasifikasi' => $post['klasifikasi'],
            'operator' => 'frontdesk',
            'file_surat' => $this->_uploadFile(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke tabel surat_keputusan dan hapus record di tabel space_sk
        $this->Sk_model->save($data);
        $this->SpaceSk_model->delete($sk_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan SK dengan nomor ' . $nomor_sk);
        
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect('sk');
    }

    public function delete() {
        // get id pada url
        $sk_id = $this->uri->segment(3);

        // jalankan fungsi delete
        $this->SpaceSk_model->delete($sk_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus space SK');

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('tgl_sk');
        $this->session->unset_userdata('nomor_agenda');
        redirect($this->_cname);
    }

}
