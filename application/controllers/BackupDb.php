<?php defined('BASEPATH') or exit('No direct script access allowed');

class BackupDb extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in(); 
    }

    public function index() {
        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $data = [
            'title' => 'Backup Database',
            'user' => $user
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/backupdb');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function backup() {
        $this->load->dbutil();
        $prefs = [
            'format' => 'sql',
            'filename' => 'bkw4_surat-' . date('Ymd-His') . '.sql'
        ];

        $backup =& $this->dbutil->backup($prefs);

        $dbname = 'bkw4_surat-' . date('Ymd-His') . '.sql';
        $save = FCPATH . 'assets/db/' . $dbname;
        $this->load->helper('file');
        write_file($save, $backup);

        $this->load->helper('download');
        force_download($dbname, $backup);
    }

}