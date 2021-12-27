<?php

function is_logged_in() {
    $ci = get_instance();

    // jika user belum login, redirect ke halaman login
    if (!$ci->session->userdata('username')) {
        redirect('auth');
    } else {
        $role = $ci->session->userdata('role');
        $menu = $ci->uri->segment(1);
        $user = $ci->db->get_where('user', ['username' => $ci->session->userdata('username')])->row_array();

        /* jika role = admin, block semua menu yang bukan haknya */
        if ($role == 'Admin') {
            if ($menu != 'home' and $menu != 'suratkeluar'
                and $menu != 'spacekeluar' and $menu != 'sk'
                and $menu != 'spt' and $menu != 'spacespt'
                and $menu != 'laporankeluar' and $menu != 'laporansk' and $menu != 'laporanspt') {
                redirect('auth/blocked');
            }
            
        /* jika role = user, block semua menu yang bukan haknya */
        } elseif ($role == 'User') {
            if ($user['username'] == 'sekretaris' or ($user['bidang'] == 12301 and $user['sub_bidang'] == 0)) { // sekretaris
                if ($menu != 'home' and $menu != 'Disposisi' and $menu != 'DisposisiUnd' and $menu != 'InboxKabid' and $menu != 'InboxUndKabid') {
                    redirect('auth/blocked');
                }
            } elseif ($user['bidang'] == 123 and $user['sub_bidang'] == 0) { // kepala badan
                if ($menu != 'Home' and $menu != 'Disposisi' and $menu != 'DisposisiUnd' and $menu != 'InboxKaban' and $menu != 'InboxUndKaban') {
                    redirect('auth/blocked');
                }
            } elseif ($user['bidang'] != 123 and $user['sub_bidang'] == 0) { // kepala bidang
                if ($menu != 'Home' and $menu != 'inboxkabid' and $menu != 'inboxundkabid') {
                    redirect('auth/blocked');
                }
            } elseif ($user['bidang'] != 0 and $user['sub_bidang'] != 0) { // kepala sub bidang
                if ($menu != 'Home' and $menu != 'InboxKasubid' and $menu != 'InboxUndKasubid') {
                    redirect('auth/blocked');
                }
            }
        }
    }

}
