<?php defined('BASEPATH') or exit('No direct script access allowed');

class Log_model extends CI_Model {

    private $_table = 'log_activity';

    public function save($param) {
        $this->db->insert($this->_table, $param);
    }

    public function getAll($limit, $start, $tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('username, modul, tipe, aktivitas, created_at, created_time');
            $this->db->from($this->_table);
            $this->db->where('created_at >=', $tgl_awal);
            $this->db->where('created_at <=', $tgl_akhir);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
        } else {
            $this->db->select('username, modul, tipe, aktivitas, created_at, created_time');
            $this->db->from($this->_table);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function getAllForExport($tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('username, modul, tipe, aktivitas, created_at, created_time');
            $this->db->from($this->_table);
            $this->db->where('created_at >=', $tgl_awal);
            $this->db->where('created_at <=', $tgl_akhir);
            $this->db->order_by('created_at', 'ASC');
        } else {
            $this->db->select('username, modul, tipe, aktivitas, created_at, created_time');
            $this->db->from($this->_table);
            $this->db->order_by('created_at', 'ASC');
        }
        return $this->db->get()->result();
    }

    public function getTotalRows() {
        $this->db->select('username, modul, tipe, aktivitas, created_at')
                 ->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getTotalRowsBySearch($tgl_awal, $tgl_akhir) {
        $this->db->where('created_at >=', $tgl_awal)
                 ->where('created_at <=', $tgl_akhir)
                 ->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getByDate($tgl_awal, $tgl_akhir, $limit, $start) {
        $this->db->select('username, modul, tipe, aktivitas, created_at');
        $this->db->from($this->_table);
        $this->db->where('created_at >=', $tgl_awal);
        $this->db->where('created_at <=', $tgl_akhir);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

}