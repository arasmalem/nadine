<?php defined('BASEPATH') or exit('No direct script access allowed');

class NotaDinas_model extends CI_Model {

    private $_table = 'nota_dinas';

    public function getAll($limit, $start, $nomor_notdin = null, $perihal = null, $tgl = null) {
        if ($nomor_notdin == null and $perihal == null and $tgl == null) {
            $this->db->select('nota_dinas.*, sifat_surat.id AS id_sifat, sifat_surat.sifat')
                    ->from($this->_table)
                    ->join('sifat_surat', 'sifat_surat.id = nota_dinas.sifat_id')
                    ->order_by('id', 'DESC')
                    ->limit($limit, $start);
        } else {
            $this->db->select('nota_dinas.*, sifat_surat.id AS id_sifat, sifat_surat.sifat');
            $this->db->from($this->_table);
            $this->db->join('sifat_surat', 'sifat_surat.id = nota_dinas.sifat_id');
            if ($tgl != null) $this->db->where('tgl_notdin', $tgl);
            if ($nomor_notdin != null) $this->db->like('nomor_notdin', $nomor_notdin, 'after');
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

    public function getTotalRowsBySearch($nomor_notdin = null, $perihal = null, $tgl = null) {
        if ($tgl != null) $this->db->where('tgl_notdin', $tgl);
        if ($nomor_notdin != null) $this->db->like('nomor_notdin', $nomor_notdin, 'after');
        if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->row();
    }

    public function getKlasifikasi() {
        return $this->db->get('klasifikasi_surat')->result();
    }

    public function getSifat() {
        return $this->db->get('sifat_surat')->result();
    }

    public function getNomorAgenda($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM nota_dinas WHERE YEAR(tgl_notdin) = '$thn'");
        return $sql->row();
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