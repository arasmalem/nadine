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
            if (
                $menu != 'home' and $menu != 'suratkeluar' and $menu != 'notadinas'
                and $menu != 'spacekeluar' and $menu != 'sk' and $menu !='spacesk' and $menu != 'spt' and $menu != 'spacespt'
                and $menu != 'laporankeluar' and $menu != 'laporansk' and $menu != 'laporanspt' and $menu != 'profile'
                and $menu != 'chat'
            ) {
                redirect('auth/blocked');
            }
            
        /* jika role = user, block semua menu yang bukan haknya */
        } elseif ($role == 'User') {
            if (
                $menu != 'home' and $menu != 'suratkeluar' and $menu != 'notadinas'
                and $menu != 'spacekeluar' and $menu != 'sk' and $menu != 'spacesk' and $menu != 'spt' 
                and $menu != 'spacespt' and $menu != 'profile' and $menu != 'chat'
            ) {
                redirect('auth/blocked');
            }
        }
    }

}
