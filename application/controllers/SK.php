<?php defined('BASEPATH') or exit('No direct script access allowed');

class SK extends CI_Controller {

    private $_cname = 'sk';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('Sk_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->Sk_model->getTotalRows();
        $config['base_url'] = base_url() . '/SK/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $sk = $this->Sk_model->getAll($config['per_page'], $this->uri->segment(3));
        $klasifikasi = $this->Sk_model->getKlasifikasi();
        $no_agenda_space = $this->Sk_model->getNomorAgendaSpace(date('Y'));
        $no_agenda = $this->Sk_model->getNomorAgenda(date('Y'));
        $tgl_sekarang = $this->Sk_model->getByTglToday();
        $tgl_max = $this->Sk_model->getTglMax();
        $bidang = $this->Sk_model->getBidang();

        // cek nomor agenda yang terakhir
        if (date('d') == '02' and date('m') == '01') {
            if (empty($no_agenda_space->nomor) and empty($no_agenda->nomor)) {
                $no_agenda = 1;
            } elseif (empty($no_agenda_space->nomor) and !empty($no_agenda->nomor)) {
                $no_agenda = $no_agenda->nomor + 1;
            } elseif (!empty($no_agenda_space->nomor) and !empty($no_agenda->nomor)) {
                // cek apakah nomor agenda terakhir ada titik atau tidak
                $exp = explode('.', $no_agenda_space->nomor);
                if (empty($exp[1])) { // jika tidak ada titik
                    $no_agenda = $no_agenda_space->nomor + 1;
                } else {
                    $no_agenda = $exp[0] + 1;
                }                
            }
        } else {
            // cek apakah nomor agenda terakhir ada titik atau tidak
            if ($tgl_max->tgl == date('Y-m-d')) {
                $no_agenda = $no_agenda->nomor + 1;
            } else {
                if (!empty($tgl_sekarang->tgl)) {
                    $no_agenda = $no_agenda->nomor + 1;
                } else {
		            if (!empty($tgl_sekarang->tgl)) {
                        $no_agenda = $no_agenda->nomor + 1;
                    } else {
                        $exp = explode('.', $no_agenda_space->nomor);
                        if (empty($exp[1])) { // jika tidak ada titik
                            $no_agenda = $no_agenda_space->nomor + 1;
                        } else {
                            $no_agenda = $exp[0] + 1;
                        }
		            }
                }
            }
        }

        $data = [
            'title' => 'Surat Keputusan',
            'user' => $user,
            'sk' => $sk,
            'klasifikasi' => $klasifikasi,
            'bidang' => $bidang,
            'no_agenda' => $no_agenda,
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
        $this->load->view('admin/sk', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl_sk'] == '' and $post['nomor_sk'] == '' and $post['perihal'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl_sk' => $post['tgl_sk'],
                    'nomor_sk' => $post['nomor_sk'],
                    'perihal' => $post['perihal'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl_sk' => $this->session->userdata('tgl_sk'),
                'nomor_sk' => $this->session->userdata('nomor_sk'),
                'perihal' => $this->session->userdata('perihal'),
            ];
        }

        $total_rows = $this->Sk_model->getTotalRowsBySearch($data['nomor_sk'], $data['perihal'], $data['tgl_sk']);
        $config['base_url'] = base_url() . '/sk/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $sk = $this->Sk_model->getAll($config['per_page'], $this->uri->segment(3), $data['nomor_sk'], $data['perihal'], $data['tgl_sk']);
        $klasifikasi = $this->Sk_model->getKlasifikasi();
        $bidang = $this->Sk_model->getBidang();
        $data = [
            'title' => 'Surat Keputusan',
            'user' => $user,
            'sk' => $sk,
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
        $this->load->view('admin/sk', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();
        $no_agenda_space = $this->Sk_model->getNomorAgendaSpace(date('Y'));
        $no_agenda = $this->Sk_model->getNomorAgenda(date('Y'));
        $tgl_sekarang = $this->Sk_model->getByTglToday();
        $tgl_max = $this->Sk_model->getTglMax();

        // cek nomor agenda yang terakhir
        if (date('d') == '02' and date('m') == '01') {
            if (empty($no_agenda_space->nomor) and empty($no_agenda->nomor)) {
                $no_agenda = 1;
            } elseif (empty($no_agenda_space->nomor) and !empty($no_agenda->nomor)) {
                $no_agenda = $no_agenda->nomor + 1;
            } elseif (!empty($no_agenda_space->nomor) and !empty($no_agenda->nomor)) {
                // cek apakah nomor agenda terakhir ada titik atau tidak
                $exp = explode('.', $no_agenda_space->nomor);
                if (empty($exp[1])) { // jika tidak ada titik
                    $no_agenda = $no_agenda_space->nomor + 1;
                } else {
                    $no_agenda = $exp[0] + 1;
                }                
            }
        } else {
            // cek apakah nomor agenda terakhir ada titik atau tidak
            if ($tgl_max->tgl == date('Y-m-d')) {
                //$no_agenda = $no_agenda->nomor + 1;
                if ($no_agenda->nomor > $no_agenda_space->nomor) {
                    $no_agenda = $no_agenda->nomor + 1;
                } else {
                    $no_agenda = $no_agenda_space->nomor + 1;
                }
            } else {
                if (!empty($tgl_sekarang->tgl)) {
                    //$no_agenda = $no_agenda->nomor + 1;
                    if ($no_agenda->nomor > $no_agenda_space->nomor) {
                        $no_agenda = $no_agenda->nomor + 1;
                    } else {
                        $no_agenda = $no_agenda_space->nomor + 1;
                    }
                } else {
                    if($no_agenda->nomor > $no_agenda_space->nomor){
                        $no_agenda = $no_agenda->nomor +1;
                    } else {
                        $exp = explode('.', $no_agenda_space->nomor);
                        if (empty($exp[1])) { // jika tidak ada titik
                            $no_agenda = $no_agenda_space->nomor + 1;
                        } else {
                            $no_agenda = $exp[0] + 1;
                        }
                    }
                }
            }
        }
        
        $nomor_sk = $post['klasifikasi'] . '/' . $no_agenda . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $no_agenda,
            'nomor_sk' => $nomor_sk,
            'tgl_sk' => $post['tgl_sk'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'klasifikasi' => $post['klasifikasi'],
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke database
        $this->Sk_model->save($data);

        // insert into log_activity table
        activity_log(@$this->uri->segment(1), 'insert', 'menambahkan SK dengan nomor ' . $post['nomor_sk']);

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
            $config['upload_path'] = './files_sk';
            $config['file_name'] = 'surat_keputusan-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
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
                $config['upload_path'] = './files_sk';
                $config['file_name'] = 'surat_keputusan-' . time() . '-' . rand(1, 1000) . $_FILES['file']['name'];
                // $config['overwrite'] = true;

                // load library upload
                $this->load->library('upload', $config);

                // cek apakah upload berhasil / gagal
                if ($this->upload->do_upload('file')) {
                    unlink(FCPATH . './files_sk/' . $this->input->post('old_file'));
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
        $sk_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_sk = $post['klasifikasi'] . '/' . $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_sk' => $nomor_sk,
            'klasifikasi' => $post['klasifikasi'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // update ke database
        $this->Sk_model->update($data, $sk_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah SK dengan nomor ' . $post['nomor_sk']);

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        // get id pada url
        $sk_id = $this->uri->segment(3);

        // get nama file di database
        $row = $this->Sk_model->getById($sk_id);
        
        // hapus file di folder
        unlink(FCPATH . './files_sk/' . $row->file_surat);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus SK dengan nomor ' . $row->nomor);

        // insert into space_keluar table, so the number can be used again
        $this->load->model('SpaceSk_model');
        $data = [
            'nomor_agenda' => $row->nomor_agenda,
            'tgl_sk' => $row->tgl_sk,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->SpaceSk_model->save($data);

        // jalankan fungsi delete
        $this->Sk_model->delete($sk_id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('nomor_sk');
        $this->session->unset_userdata('tgl_sk');
        $this->session->unset_userdata('perihal');
        redirect($this->_cname);
    }

}
