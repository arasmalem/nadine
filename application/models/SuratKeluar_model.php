<?php defined('BASEPATH') or exit('No direct script access allowed');

class SuratKeluar_model extends CI_Model {

    private $_table = 'surat_keluar';
    private $_table2 = 'bidang';

    public function getAll($limit, $start, $tujuan = null, $nomor_surat_keluar = null, $perihal = null, $tgl = null) {
        if ($tujuan == null and $nomor_surat_keluar == null and $perihal == null and $tgl == null) {
            $this->db->select('surat_keluar.*, sifat_surat.id AS id_sifat, sifat_surat.sifat')
                    ->from($this->_table)
                    ->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id')
                    ->order_by('id', 'DESC')
                    ->limit($limit, $start);
        } else {
            $this->db->select('surat_keluar.*, sifat_surat.id AS id_sifat, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = surat_keluar.sifat_id');
            if ($tgl != null) $this->db->where('tgl', $tgl);
            if ($tujuan != null) $this->db->like('tujuan', $tujuan, 'both');
            if ($nomor_surat_keluar != null) $this->db->like('nomor_surat_keluar', $nomor_surat_keluar, 'after');
            if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get()->result();
        return $result;
    }

    public function getTotalRows() {
        return $this->db->count_all_results($this->_table);
    }

    public function getTotalRowsBySearch($tujuan = null, $nomor_surat_keluar = null, $perihal = null, $tgl = null) {
        if ($tgl != null) $this->db->where('tgl', $tgl);
        if ($tujuan != null) $this->db->like('tujuan', $tujuan, 'both');
        if ($nomor_surat_keluar != null) $this->db->like('nomor_surat_keluar', $nomor_surat_keluar, 'after');
        if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getByTgl($tgl) {
        return $this->db->get_where($this->_table, ['tgl' => $tgl])->result();
    }

    public function getByTglToday() {
        return $this->db->get_where($this->_table, ['tgl' => date('Y-m-d')])->row();
    }

    public function getTglMax() {
        $this->db->select_max('tgl', 'tgl')
                 ->from($this->_table);
        return $this->db->get()->row();
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->row();
    }

    public function getByNomor($nomor) {
        return $this->db->get_where($this->_table, ['nomor_surat_keluar' => $nomor])->row();
    }

     public function getByNomorAgenda($nomor_agenda) {
        return $this->db->get_where($this->_table, ['nomor_agenda' => $nomor_agenda])->row();
    }

    public function getKlasifikasi() {
        return $this->db->get('klasifikasi_surat')->result();
    }

    public function getSifat() {
        return $this->db->get('sifat_surat')->result();
    }

   public function getNomorAgendaSpace($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM space_keluar WHERE YEAR(tgl) = '$thn'");
        return $sql->row();
    }

    public function getNomorAgenda($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM surat_keluar WHERE YEAR(tgl) = '$thn'");
        return $sql->row();
    }

    public function getBidang() {
        return $this->db->get($this->_table2)->result();
    }

    public function save($data) {
        $this->db->insert($this->_table, $data);
    }

    public function update($data, $id) {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function delete($id) {
        $this->db->delete($this->_table, ['id' => $id]);
    }

}