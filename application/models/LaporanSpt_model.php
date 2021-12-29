<?php defined('BASEPATH') or exit('No direct script access allowed');

class LaporanSpt_model extends CI_Model {

    private $_table = 'surat_spt';

    public function getAll($limit, $start, $tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('nomor_agenda, nomor_spt, tgl_spt, perihal, operator, created_at');
            $this->db->from($this->_table);
            $this->db->where("DATE(created_at) >=", $tgl_awal);
            $this->db->where("DATE(created_at) <=", $tgl_akhir);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
        } else {
            $this->db->select('nomor_agenda, nomor_spt, tgl_spt, perihal, operator, created_at');
            $this->db->from($this->_table);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function getAllForExport($tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('nomor_agenda, nomor_spt, tgl_spt, perihal, operator, created_at');
            $this->db->from($this->_table);
            $this->db->where("DATE(created_at) >=", $tgl_awal);
            $this->db->where("DATE(created_at) <=", $tgl_akhir);
            $this->db->order_by("DATE(created_at)", 'ASC');
        } else {
            $this->db->select('nomor_agenda, nomor_spt, tgl_spt, perihal, operator, created_at');
            $this->db->from($this->_table);
            $this->db->order_by("DATE(created_at)", 'ASC');
        }
        return $this->db->get()->result();
    }

    public function getTotalRows() {
        $this->db->select('nomor_agenda, nomor_spt, tgl_spt, perihal, operator, created_at')
                 ->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getTotalRowsBySearch($tgl_awal, $tgl_akhir) {
        $this->db->where("DATE(created_at) >=", $tgl_awal)
                 ->where("DATE(created_at) <=", $tgl_akhir)
                 ->from($this->_table);
        return $this->db->count_all_results();
    }

}