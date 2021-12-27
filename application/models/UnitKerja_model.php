<?php defined('BASEPATH') or exit('No direct script access allowed');

class UnitKerja_model extends CI_Model {

    private $_table = 'skpd';

    public function getUnit() {
        return $this->db->get($this->_table)->result();
    }

    public function getUnitById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->result();
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