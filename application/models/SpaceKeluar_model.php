<?php defined('BASEPATH') or exit('No direct script access allowed');

class SpaceKeluar_model extends CI_Model {

    private $_table = 'space_keluar';
    private $_table2 = 'bidang';

    public function getAll($limit, $start, $tgl_surat = null, $nomor_agenda = null) {
        if ($tgl_surat == null and $nomor_agenda == null) {
            $this->db->select('*')
                ->from($this->_table)
                ->order_by('tgl', 'DESC')
                ->limit($limit, $start);
        } else {
            $this->db->select('*');
            $this->db->from($this->_table);
            if ($tgl_surat != null) $this->db->where('tgl', $tgl_surat);
            if ($nomor_agenda != null) $this->db->where('nomor_agenda', $nomor_agenda);
            $this->db->order_by('tgl', 'DESC');
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get()->result();
        return $result;
    }

    public function getTotalRows() {
        return $this->db->count_all_results($this->_table);
    }

    public function getTotalRowsBySearch($tgl = null, $nomor_agenda = null) {
        if ($tgl != null) $this->db->where('tgl', $tgl);
        if ($nomor_agenda != null) $this->db->where('nomor_agenda', $nomor_agenda);
        return $this->db->count_all_results($this->_table);
    }

    public function getNomorByCurDate() {
        return $this->db->where('tgl', date('Y-m-d'))
                        ->from($this->_table)
                        ->count_all_results();
    }

    public function getTglMax() {
        $this->db->select_max('tgl', 'tgl')
                 ->from($this->_table);
        return $this->db->get()->row();
    }

    public function getNomorMax() {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM space_keluar");
        return $sql->row();
    }

    public function getNomorByTglSurat($tgl_surat) {
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM surat_keluar WHERE tgl='$tgl_surat'");
        return $sql->row();
    }

    public function getSpaceNomorByTglSurat($tgl_surat) {
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM space_keluar WHERE tgl='$tgl_surat'");
        return $sql->row();
    }

    public function getBidang() {
        return $this->db->get($this->_table2)->result();
    }

    public function save($data) {
        $this->db->insert($this->_table, $data);
    }

    public function delete($id) {
        $this->db->delete($this->_table, ['id' => $id]);
    }

}