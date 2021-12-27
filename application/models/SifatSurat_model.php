<?php defined('BASEPATH') or exit('No direct script access allowed');

class SifatSurat_model extends CI_Model {

    private $_table = 'sifat_surat';

    public function getAll() {
        return $this->db->get($this->_table)->result();
    }

    public function save($data) {
        $this->db->insert($this->_table, $data);
    }

    public function update($id, $data) {
        $this->db->set($data);
        $this->db->where('sifat_id', $id);
        $this->db->update($this->_table);
    }

    public function delete($id) {
        $this->db->delete($this->_table, ['sifat_id' => $id]);
    }

}