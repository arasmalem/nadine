<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sk_model extends CI_Model {

    private $_table = 'surat_keputusan';
    private $_table2 = 'skpd';

    public function getAll($limit, $start, $nomor_sk = null, $perihal = null, $tgl_sk = null) {
        if ($nomor_sk == null and $perihal == null and $tgl_sk == null) {
            $this->db->select('*')
                    ->from($this->_table)
                    ->order_by('id', 'DESC')
                    ->limit($limit, $start);
        } else {
            $this->db->select('*');
            $this->db->from($this->_table);
            if ($tgl_sk != null) $this->db->where('tgl_sk', $tgl_sk);
            if ($nomor_sk != null) $this->db->like('nomor_sk', $nomor_sk, 'after');
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

    public function getTotalRowsBySearch($nomor_sk = null, $perihal = null, $tgl_sk = null) {
        if ($tgl_sk != null) $this->db->where('tgl_sk', $tgl_sk);
        if ($nomor_sk != null) $this->db->like('nomor_sk', $nomor_sk, 'after');
        if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->row();
    }

    public function getByNomor($nomor) {
        return $this->db->get_where($this->_table, ['nomor_sk' => $nomor])->row();
    }

    public function getByTglToday() {
        return $this->db->get_where($this->_table, ['tgl_sk' => date('Y-m-d')])->row();
    }

    public function getTglMax() {
        $this->db->select_max('tgl_sk', 'tgl')
                 ->from($this->_table);
        return $this->db->get()->row();
    }

    public function getKlasifikasi() {
        return $this->db->get('klasifikasi_surat')->result();
    }

    public function getNomorAgendaSpace($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM space_sk WHERE YEAR(tgl_sk) = '$thn'");
        return $sql->row();
    }

    public function getNomorAgenda($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM surat_keputusan WHERE YEAR(tgl_sk) = '$thn'");
        return $sql->row();
    }

    public function getBidang() {
        $this->db->from($this->_table2);
        $this->db->where('pId', '214');
        return $this->db->get()->result();
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