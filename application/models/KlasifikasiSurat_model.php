<?php defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiSurat_model extends CI_Model {

    private $_table = 'klasifikasi_surat';

    public function getAll($limit, $start, $kode_surat = null, $klasifikasi = null) {
        if ($kode_surat == null and $klasifikasi == null) {
            $this->db->select('*');
            $this->db->from($this->_table);
            $this->db->order_by('kode_surat', 'ASC');
            $this->db->limit($limit, $start);
        } else {
            $this->db->select('*');
            $this->db->from($this->_table);
            if ($kode_surat != null) $this->db->where('kode_surat', $kode_surat);
            if ($klasifikasi != null) $this->db->like('klasifikasi', $klasifikasi, 'both');
            $this->db->order_by('kode_surat', 'ASC');
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function getTotalRows() {
        return $this->db->count_all_results($this->_table);
    }

    public function getTotalRowsBySearch($kode_surat = null, $klasifikasi = null) {
        if ($kode_surat != null) $this->db->where('kode_surat', $kode_surat);
        if ($klasifikasi != null) $this->db->like('klasifikasi', $klasifikasi, 'both');
        return $this->db->count_all_results($this->_table);
    }

    public function save($data) {
        $this->db->insert($this->_table, $data);
    }

    public function update($id, $data) {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function delete($id) {
        $this->db->delete($this->_table, ['id' => $id]);
    }

}