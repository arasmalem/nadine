<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model {

    private $_table = 'user';

    public function getAll($limit, $start, $username = null, $nama = null) {
        if ($username == null and $nama == null) {
            $this->db->select('id, username, nama, role, status, bidang, sub_bidang, foto');
            $this->db->from($this->_table);
            $this->db->order_by('id', 'desc');
            $this->db->limit($limit, $start);
        } else {
            $this->db->select('id, username, nama, role, status, bidang, sub_bidang, foto');
            $this->db->from($this->_table);
            if ($username != null) $this->db->like('username', $username, 'both');
            if ($nama != null) $this->db->like('nama', $nama, 'both');
            $this->db->order_by('id', 'desc');
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function getTotalRows() {
        return $this->db->count_all_results($this->_table);
    }

    public function getTotalRowsBySearch($username = null, $nama = null) {
        if ($username != null) $this->db->like('username', $username, 'both');
        if ($nama != null) $this->db->like('nama', $nama, 'both');
        return $this->db->count_all_results($this->_table);
    }

    public function getByUsername($username) {
        return $this->db->get_where($this->_table, ['username' => $username])->row();
    }

    public function getUserById($id) {
        return $this->db->get_where($this->_table, ['id' => $id])->row();
    }

    public function getBidang($username) {
        $this->db->select('name')
                 ->from($this->_table)
                 ->join('skpd', 'skpd.id = user.bidang')
                 ->where('username', $username);
        $result = $this->db->get()->row();
        return $result;
    }

    public function getAllBidang() {
        return $this->db->get_where('skpd', ['pId' => 214])->result();
    }

    public function getAllSubBidang($id_bidang) {
        $this->db->select('id, name');
        $this->db->from('skpd');
        $this->db->like('pId', $id_bidang);
        return $this->db->get()->result();
    }

    public function getBidangById($id) {
        $this->db->select('name')
                 ->from($this->_table)
                 ->join('skpd', 'skpd.id = user.bidang')
                 ->where('skpd.id', $id);
        $result = $this->db->get()->row();
        return $result;
    }

    public function getSubBidang($username) {
        $this->db->select('name')
                 ->from($this->_table)
                 ->join('skpd', 'skpd.id = user.sub_bidang')
                 ->where('username', $username);
        $result = $this->db->get()->row();
        return $result;
    }

    public function getSubBidangById($id) {
        $this->db->select('name')
                 ->from($this->_table)
                 ->join('skpd', 'skpd.id = user.sub_bidang')
                 ->where('skpd.id', $id);
        $result = $this->db->get()->row();
        return $result;
    }

    public function editProfile($data, $username) {
        $this->db->set($data)
                 ->where('username', $username)
                 ->update($this->_table);
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