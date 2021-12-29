<?php defined('BASEPATH') or exit('No direct script access allowed');

class SPT extends CI_Controller {

    private $_cname = 'spt';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('Spt_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->Spt_model->getTotalRows();
        $config['base_url'] = base_url() . '/spt/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spt = $this->Spt_model->getAll($config['per_page'], $this->uri->segment(3));
        $bidang = $this->Spt_model->getBidang();

        $data = [
            'title' => 'Surat Perintah Tugas',
            'user' => $user,
            'spt' => $spt,
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
        }elseif ($this->session->userdata('role') == 'User') {
            $this->load->view('template/sidebar_user', $data);
        }
        $this->load->view('admin/spt', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl_spt'] == '' and $post['nomor_spt'] == '' and $post['perihal'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl_spt' => $post['tgl_spt'],
                    'nomor_spt' => $post['nomor_spt'],
                    'perihal' => $post['perihal']
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl_spt' => $this->session->userdata('tgl_spt'),
                'nomor_spt' => $this->session->userdata('nomor_spt'),
                'perihal' => $this->session->userdata('perihal'),
            ];
        }
        
        $total_rows = $this->Spt_model->getTotalRowsBySearch($data['tgl_spt'], $data['nomor_spt'], $data['perihal']);
        $config['base_url'] = base_url() . '/spt/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $spt = $this->Spt_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl_spt'], $data['nomor_spt'], $data['perihal']);
        $data = [
            'title' => 'Surat Perintah Tugas',
            'user' => $user,
            'spt' => $spt,
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
        $this->load->view('admin/spt', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();
        $no_agenda_space = $this->Spt_model->getNomorAgendaSpace(date('Y'));
        $no_agenda = $this->Spt_model->getNomorAgenda(date('Y'));
        $tgl_sekarang = $this->Spt_model->getByTglToday();
        $tgl_max = $this->Spt_model->getTglMax();

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
                    if($no_agenda->nomor > $no_agenda_space->nomor) {
                        $no_agenda = $no_agenda->nomor + 1;
                    } else{
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

        $nomor_spt = '094/' . $no_agenda . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $no_agenda,
            'nomor_spt' => $nomor_spt,
            'tgl_spt' => $post['tgl_spt'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke database
        $this->Spt_model->save($data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan SPT dengan nomor ' . $nomor_spt);

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

        // jika methodnya edit surat
        } elseif ($method == 'edit') {
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
                    unlink(FCPATH . './files_spt/' . $this->input->post('old_file'));
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
        $spt_id = $this->uri->segment(3);
        $post = $this->input->post();
        $nomor_spt = '094/' .  $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $post['nomor_agenda'],
            'nomor_spt' => $nomor_spt,
            'tgl_spt' => $post['tgl_spt'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // update ke database
        $this->Spt_model->update($data, $spt_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah SPT dengan nomor ' . $post['nomor_spt']);

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        // get id pada url
        $spt_id = $this->uri->segment(3);

        // get nama file di database
        $row = $this->Spt_model->getById($spt_id);
        
        // hapus file di folder
        unlink(FCPATH . './files_spt/' . $row->file_surat);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus SPT dengan nomor ' . $row->nomor_spt);

        // insert into space_spt table, so the number can be used again
        $this->load->model('SpaceSpt_model');
        $data = [
            'nomor_agenda' => $row->nomor_agenda,
            'tgl_spt' => $row->tgl_spt,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->SpaceSpt_model->save($data);

        // jalankan fungsi delete
        $this->Spt_model->delete($spt_id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('tgl_spt');
        $this->session->unset_userdata('nomor_spt');
        $this->session->unset_userdata('perihal');
        redirect($this->_cname);
    }

}
