<?php

function activity_log($modul, $tipe, $aktivitas) {
    $ci = get_instance();

    $params['username'] = $ci->session->userdata('username');
    $params['modul'] = $modul;
    $params['tipe'] = $tipe;
    $params['aktivitas'] = $aktivitas;
    $params['created_at'] = date('Y-m-d');
    $params['created_time'] = date('H:i:s');

    // load model
    // $ci->load->model('log_model');

    // save into database
    $ci->Log_model->save($params);

}
