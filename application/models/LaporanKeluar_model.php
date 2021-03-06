<?php defined('BASEPATH') or exit('No direct script access allowed');

class LaporanKeluar_model extends CI_Model {

    private $_table = 'surat_keluar';

    public function getAll($limit, $start, $tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('surat_keluar.*, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id');
            $this->db->where("DATE(surat_keluar.created_at) >=", $tgl_awal);
            $this->db->where("DATE(surat_keluar.created_at) <=", $tgl_akhir);
            $this->db->order_by('surat_keluar.id', 'DESC');
            $this->db->limit($limit, $start);
        } else {
            $this->db->select('surat_keluar.*, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id');
            $this->db->order_by('surat_keluar.id', 'DESC');
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function getAllForExport($tgl_awal = null, $tgl_akhir = null) {
        if ($tgl_awal != null and $tgl_akhir != null) {
            $this->db->select('surat_keluar.*, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id');
            $this->db->where("DATE(surat_keluar.created_at) >=", $tgl_awal);
            $this->db->where("DATE(surat_keluar.created_at) <=", $tgl_akhir);
            $this->db->order_by("DATE(surat_keluar.created_at)", 'ASC');
        } else {
            $this->db->select('surat_keluar.*, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id');
            $this->db->order_by("DATE(surat_keluar.created_at)", 'ASC');
        }
        return $this->db->get()->result();
    }

    public function getTotalRows() {
        $this->db->select('tujuan, tgl, nomor_agenda, nomor_surat_keluar, sifat_id, perihal, ringkasan, klasifikasi, operator, created_at')
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