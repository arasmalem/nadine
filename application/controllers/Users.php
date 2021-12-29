<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller {

    private $_cname = 'users';

    public function __construct() {
        parent::__construct();
        is_logged_in();
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->Users_model->getTotalRows();
        $config['base_url'] = base_url() . '/Users/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        // initialize
        $this->pagination->initialize($config);
        /* END OF PAGINATION AND SEARCH BOX */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $all_users = $this->Users_model->getAll($config['per_page'], $this->uri->segment(3));
        $all_bidang = $this->Users_model->getAllBidang();
        
        $data = [
            'title' => 'Manajemen User',
            'user' => $user,
            'all_users' => $all_users,
            'all_bidang' => $all_bidang,
            'total_rows' => $config['total_rows'],
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3)
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/users', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        $post = $this->input->post();
        if ($this->input->post('submit')) {
            if ($post['username'] == '' and $post['nama'] == '') {
                redirect($this->_cname);
            } else {
                $data = [
                    'user_name' => $post['username'],
                    'nama' => $post['nama'],
                ];
                $this->session->set_userdata($data);
            }
        } else {
            $data = [
                'user_name' => $this->session->userdata('user_name'),
                'nama' => $this->session->userdata('nama'),
            ];
        }

        $total_rows = $this->Users_model->getTotalRowsBySearch($data['user_name'], $data['nama']);
        $config['base_url'] = base_url() . '/users/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $all_users = $this->Users_model->getAll($config['per_page'], $this->uri->segment(3), $data['user_name'], $data['nama']);
        $data = [
            'title' => 'Manajemen Users',
            'user' => $user,
            'all_users' => $all_users,
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3),
            'total_rows' => $config['total_rows']
        ];


        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('template/sidebar_superadmin', $data);
        $this->load->view('superadmin/users', $data);
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function listSubBidang() {
        $sub_bidang = $this->Users_model->getAllSubBidang($this->input->post('pId'));
        echo json_encode($sub_bidang);
    }

    public function getListSubid() {
        $pId = $this->input->post('pId');
        $subid = $this->Users_model->getAllSubBidang($pId);
        $data = "<option value='0'>- Pilih Sub Bidang / Sub Bagian -</option>";
        foreach ($subid as $row) :
            $data .= "<option value='$row->id'>$row->name</option>";
        endforeach;
        echo $data;
    }

    public function add() {
        $data = [
            'username' => htmlspecialchars(trim($this->input->post('username')), true),
            'password' => password_hash(trim($this->input->post('password')), PASSWORD_DEFAULT),
            'nama' => htmlspecialchars($this->input->post('nama'), true),
            'role' => $this->input->post('role'),
            'bidang' => $this->input->post('bidang'),
            'sub_bidang' => $this->input->post('sub_bidang'),
            'status' => $this->input->post('status'),
            'foto' => $this->_uploadImage(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        // insert into database
        $this->Users_model->save($data);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'insert', 'menambahkan user ' . $this->input->post('username'));
        
        $this->session->set_flashdata('success', 'User berhasil ditambahkan!');
        redirect($this->_cname);
    }

    private function _uploadImage() {
        $method = $this->uri->segment(2);
        $upload_image = $_FILES['foto']['name'];

        // jika tambah user
        if ($method == 'add') {
            // jika ada gambar yg diupload
            if ($upload_image) {
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size'] = 200;
                $config['upload_path'] = './assets/images/';
                // $config['overwrite'] = true;
    
                $this->load->library('upload', $config);
                // jika upload berhasil
                if($this->upload->do_upload('foto')) {
                    return $this->upload->data('file_name');
                }
                else { // jika gagal
                    $this->session->set_flashdata('failed', $this->upload->display_errors());
                    redirect($this->_cname);
                }
            }
            else {
                return 'default.png';
            }
        }
        // jika edit user
        elseif ($method == 'edit') {
            // jika ada gambar yg diupload
            if ($upload_image) {
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size'] = 200;
                $config['upload_path'] = './assets/images/';

                if ($this->input->post('old_image') != 'default.png') {
                    unlink(FCPATH . 'assets/images/' . $this->input->post('old_image'));
                }

                $this->load->library('upload', $config);
                // jika upload berhasil
                if( $this->upload->do_upload('foto')) {
                    return $this->upload->data('file_name');
                }
                else { // jika gagal
                    $this->session->set_flashdata('failed', $this->upload->display_errors());
                    redirect($this->_cname);
                }
            } 
            else {
                return $this->input->post('old_image');
            }
        }

    }

    public function edit() {
        $id = $this->uri->segment(3); //ambil id user dari url
        $user = $this->Users_model->getUserById($id);

        $pass = $this->input->post('password');
        if ($pass != '' OR $pass != NULL) :
            $password = password_hash($pass, PASSWORD_DEFAULT);
        else :
            $password = $user->password;
        endif;

        $data = [
            'password' => $password,
            'nama' => htmlspecialchars($this->input->post('nama'), true),
            'bidang' => $this->input->post('bidang'),
            'sub_bidang' => $this->input->post('sub_bidang'),
            'role' => $this->input->post('role'),
            'status' => $this->input->post('status'),
            'foto' => $this->_uploadImage()
        ];
        // update tabel user
        $this->Users_model->update($data, $id);

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'update', 'mengubah user ' . $this->input->post('username'));

        $this->session->set_flashdata('success', 'User berhasil diubah!');
        redirect($this->_cname);
    }

    public function delete() {
        $id = $this->uri->segment(3);
        $user = $this->Users_model->getUserById($id);

        // jika foto user bukan default, hapus!
        if ($user->foto != 'default.png') :
            unlink(FCPATH . 'assets/images/' . $user->foto);
        endif;

        // insert into log_activity table
        activity_log($this->uri->segment(1), 'delete', 'menghapus user ' . $user->username);

        // hapus user berdasar id
        $this->Users_model->delete($id);

        $this->session->set_flashdata('success', 'User berhasil dihapus!');
        redirect($this->_cname);        
    }

    public function reset() {
        $this->session->unset_userdata('keyword');
        redirect($this->_cname);
    }

}
