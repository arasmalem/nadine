<?php defined('BASEPATH') or exit('No direct script access allowed');

class SuratKeluar extends CI_Controller {

    private $_cname = 'suratkeluar';

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('SuratKeluar_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->SuratKeluar_model->getTotalRows();
        $config['base_url'] = base_url() . '/suratkeluar/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $srtKeluar = $this->SuratKeluar_model->getAll($config['per_page'], $this->uri->segment(3));
        $klasifikasi = $this->SuratKeluar_model->getKlasifikasi();
        $sifat = $this->SuratKeluar_model->getSifat();
        $no_agenda_space = $this->SuratKeluar_model->getNomorAgendaSpace(date('Y'));
        $no_agenda = $this->SuratKeluar_model->getNomorAgenda(date('Y'));
        $tgl_sekarang = $this->SuratKeluar_model->getByTglToday();
        $tgl_max = $this->SuratKeluar_model->getTglMax();
        $bidang = $this->SuratKeluar_model->getBidang();

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

        $data = [
            'title' => 'Surat Keluar',
            'user' => $user,
            'srtKeluar' => $srtKeluar,
            'klasifikasi' => $klasifikasi,
            'sifat' => $sifat,
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
        $this->load->view('admin/surat_keluar', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['tgl'] == '' and $post['nomor_surat_keluar'] == '' and $post['perihal'] == '' and $post['tujuan'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'tgl' => $post['tgl'],
                    'nomor_surat_keluar' => $post['nomor_surat_keluar'],
                    'perihal' => $post['perihal'],
                    'tujuan' => $post['tujuan']
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'tgl' => $this->session->userdata('tgl'),
                'nomor_surat_keluar' => $this->session->userdata('nomor_surat_keluar'),
                'perihal' => $this->session->userdata('perihal'),
                'tujuan' => $this->session->userdata('tujuan')
            ];
        }

        $total_rows = $this->SuratKeluar_model->getTotalRowsBySearch($data['tujuan'], $data['nomor_surat_keluar'], $data['perihal'], $data['tgl']);
        $config['base_url'] = base_url() . '/suratkeluar/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 1;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $srtKeluar = $this->SuratKeluar_model->getAll($config['per_page'], $this->uri->segment(3), $data['tujuan'], $data['nomor_surat_keluar'], $data['perihal'], $data['tgl']);
        $klasifikasi = $this->SuratKeluar_model->getKlasifikasi();
        $bidang = $this->SuratKeluar_model->getBidang();
        $sifat = $this->SuratKeluar_model->getSifat();
        $data = [
            'title' => 'Surat Keluar',
            'user' => $user,
            'srtKeluar' => $srtKeluar,
            'bidang' => $bidang,
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
        $this->load->view('admin/surat_keluar', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function add() {
        $post = $this->input->post();
        $no_agenda_space = $this->SuratKeluar_model->getNomorAgendaSpace(date('Y'));
	    $no_agenda = $this->SuratKeluar_model->getNomorAgenda(date('Y'));
	    $tgl_sekarang = $this->SuratKeluar_model->getByTglToday();
        $tgl_max = $this->SuratKeluar_model->getTglMax();

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

        $nomor_surat_keluar = $post['klasifikasi'] . '/' . $no_agenda . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'nomor_agenda' => $no_agenda,
            'tujuan' => htmlspecialchars(strtoupper($post['tujuan']), true),
            'nomor_surat_keluar' => $nomor_surat_keluar,
            'tgl' => $post['tgl_surat'],
            'sifat_id' => $post['sifat'],
            'lampiran' => $post['lampiran'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'ringkasan' => htmlspecialchars($post['ringkasan'], true),
            'klasifikasi' => $post['klasifikasi'],
            'file_surat' => $this->_uploadFile(),
            'operator' => $this->session->userdata('username'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // insert ke database
        $this->SuratKeluar_model->save($data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan surat keluar dengan nomor ' . $nomor_surat_keluar);

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

        // jika methodnya edit surat
        } elseif ($method == 'edit') {
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
                    unlink(FCPATH . './files_surat_keluar/' . $this->input->post('old_file'));
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
        $nomor_surat_keluar = $post['klasifikasi'] . '/' . $post['nomor_agenda'] . '/' . $post['bidang'] . '/' . date('Y');
        $data = [
            'tujuan' => htmlspecialchars(strtoupper($post['tujuan']), true),
            'nomor_surat_keluar' => $nomor_surat_keluar,
            'tgl' => $post['tgl_surat'],
            'sifat_id' => $post['sifat'],
            'lampiran' => $post['lampiran'],
            'perihal' => htmlspecialchars($post['perihal'], true),
            'ringkasan' => htmlspecialchars($post['ringkasan'], true),
            'klasifikasi' => $post['klasifikasi'],
            'operator' => $this->session->userdata('username'),
            'file_surat' => $this->_uploadFile(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // update ke database
        $this->SuratKeluar_model->update($data, $surat_id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah surat keluar dengan nomor ' . $nomor_surat_keluar);

        $this->session->set_flashdata('success', 'Data berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        // get id pada url
        $surat_id = $this->uri->segment(3);

        // get nama file di database
        $row = $this->SuratKeluar_model->getById($surat_id);
        
        // hapus file di folder
        unlink(FCPATH . './files_surat_keluar/' . $row->file_surat);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus surat keluar dengan nomor ' . $row->nomor_surat_keluar);

        // insert into space_keluar table, so the number can be used again
        $this->load->model('SpaceKeluar_model');
        $data = [
            'nomor_agenda' => $row->nomor_agenda,
            'tgl' => $row->tgl,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->SpaceKeluar_model->save($data);

        // jalankan fungsi delete
        $this->SuratKeluar_model->delete($surat_id);

        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect($this->_cname);
    }

    public function reset() {
        $this->session->unset_userdata('tujuan');
        $this->session->unset_userdata('nomor_surat_keluar');
        $this->session->unset_userdata('perihal');
        $this->session->unset_userdata('tgl');
        redirect($this->_cname);
    }

}
