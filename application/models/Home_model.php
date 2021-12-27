<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home_model extends CI_Model {

    private $_table2 = 'surat_keluar';
    private $_table3 = 'surat_spt';
    private $_table4 = 'surat_keputusan';
    private $_table5 = 'user';

    public function getJmlSuratKeluar() {
        return $this->db->count_all_results($this->_table2);
    }

    public function getJmlSuratKeluarByThn($thn) {
        $this->db->where("YEAR(tgl)", $thn);
        return $this->db->count_all_results($this->_table2);
    }

    public function getJmlSpt() {
        return $this->db->count_all_results($this->_table3);
    }

    public function getJmlSk() {
        return $this->db->count_all_results($this->_table4);
    }

    public function getJmlUser() {
        return $this->db->count_all_results($this->_table5);
    }

    public function getLatestActivity($limit) {
        $this->db->select('username, modul, tipe, aktivitas, created_at, created_time');
        $this->db->from('log_activity');
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

}