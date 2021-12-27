<?php defined('BASEPATH') or exit('No direct script access allowed');

class Spt_model extends CI_Model {

    private $_table = 'surat_spt';
    private $_table2 = 'bidang';

    public function getAll($limit, $start, $tgl_spt = null, $nomor_spt = null, $perihal = null) {
        if ($tgl_spt == null and $nomor_spt == null and $perihal == null) {
            $this->db->select('*')
                    ->from($this->_table)
                    ->order_by('id', 'DESC')
                    ->limit($limit, $start);
        } else {
            $this->db->select('*');
            $this->db->from($this->_table);
            if ($tgl_spt != null) $this->db->where('tgl_spt', $tgl_spt);
            if ($nomor_spt != null) $this->db->like('nomor_spt', $nomor_spt, 'after');
            if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);    
        }

        $result = $this->db->get()->result();
        return $result;
    }

    public function getTotalRows() {
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }
    
    public function getTotalRowsBySearch($tgl_spt = null, $nomor_spt = null, $perihal = null) {
        if ($tgl_spt != null) $this->db->where('tgl_spt', $tgl_spt);
        if ($nomor_spt != null) $this->db->like('nomor_spt', $nomor_spt, 'after');
        if ($perihal != null) $this->db->like('perihal', $perihal, 'both');
        return $this->db->count_all_results($this->_table);
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->row();
    }

    public function getByTglToday() {
        return $this->db->get_where($this->_table, ['tgl_spt' => date('Y-m-d')])->row();
    }

    public function getTglMax() {
        $this->db->select_max('tgl_spt', 'tgl')
                 ->from($this->_table);
        return $this->db->get()->row();
    }

    public function getByNomor($nomor) {
        return $this->db->get_where($this->_table, ['nomor_spt' => $nomor])->row();
    }

    public function getNomorAgendaSpace($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM space_spt WHERE YEAR(tgl_spt) = '$thn'");
        return $sql->row();
    }

    public function getNomorAgenda($thn) {
        // convert nomor_agenda dari string ke integer
        $sql = $this->db->query("SELECT MAX(CONVERT(nomor_agenda, signed)) as nomor FROM surat_spt WHERE YEAR(tgl_spt) = '$thn'");
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