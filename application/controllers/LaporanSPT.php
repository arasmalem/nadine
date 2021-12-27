<?php defined('BASEPATH') or exit('No direct script access allowed');

class LaporanSPT extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('LaporanSpt_model');
        //$this->load->model('Users_model');
        //$this->load->model('Log_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->LaporanSpt_model->getTotalRows();
        $config['base_url'] = base_url() . '/LaporanSpt/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 7;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $laporan = $this->LaporanSpt_model->getAll($config['per_page'], $this->uri->segment(3));

        $data = [
            'title' => 'Laporan SPT',
            'user' => $user,
            'laporan' => $laporan,
            'total_rows' => $config['total_rows'],
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3)
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        if ($this->session->userdata('role') == 'Admin') {
            $this->load->view('template/sidebar_admin', $data);
        } else {
            $this->load->view('template/sidebar_superadmin', $data);
        }
        $this->load->view('superadmin/laporan_spt');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function search() {
        if ($this->input->post('submit')) {
            /* simpan input dari user ke dalam variabel */
            $data['tgl_awal'] = $this->input->post('tgl_awal');
            $data['tgl_akhir'] = $this->input->post('tgl_akhir');

            /* simpan variabel ke dalam session utk digunakan pagination */
            $this->session->set_userdata('tgl_awal', $data['tgl_awal']);
            $this->session->set_userdata('tgl_akhir', $data['tgl_akhir']);
        } else {
            /* simpan session yang telah diset saat tombol cari diklik ke dalam variabel
                agar bisa digunakan pagination */
            $data['tgl_awal'] = $this->session->userdata('tgl_awal');
            $data['tgl_akhir'] = $this->session->userdata('tgl_akhir');
        }

        /* PAGINATION */
        // config
        $total_rows = $this->LaporanSpt_model->getTotalRowsBySearch($data['tgl_awal'], $data['tgl_akhir']);
        $config['base_url'] = base_url() . '/LaporanSPT/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 7;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $laporan = $this->LaporanSpt_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl_awal'], $data['tgl_akhir']);

        $data = [
            'title' => 'Laporan SPT',
            'user' => $user,
            'laporan' => $laporan,
            'total_rows' => $config['total_rows'],
            'pagination' => $this->pagination->create_links(),
            'start' => $this->uri->segment(3)
        ];
        $this->load->view('template/header', $data);
        $this->load->view('template/topbar', $data);
        if ($this->session->userdata('role') == 'Admin') {
            $this->load->view('template/sidebar_admin', $data);
        } else {
            $this->load->view('template/sidebar_superadmin', $data);
        }
        $this->load->view('superadmin/laporan_spt');
        $this->load->view('template/footer');
        $this->load->view('modal-logout');
    }

    public function export() {
        /* load library PHPExcel */
        include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        /* panggil class PHPExcel */
        $excel = new PHPExcel();

        /* settingan awal file excel */
        $excel->getProperties()->setCreator('BKD Jatim')
                               ->setLastModifiedBy('BKD Jatim')
                               ->setTitle('Rekap Laporan SPT')
                               ->setSubject('Laporan')
                               ->setDescription('Rekap Laporan SPT')
                               ->setKeywords('Laporan SPT');

        /* membuat variabel untuk menampung pengaturan style header tabel */
        $style_col = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ];

        /* membuat variabel untuk menampung pengaturan isi tabel */
        $style_row = [
            // 'font' => ['bold' => true],
            'alignment' => ['vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ];

        /* mewarnai header kolom */
        $style_header = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '03A5FC']
            ]
        ];

        /* setting judul laporan */
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "REKAP LAPORAN SPT");
        $excel->getActiveSheet()->mergeCells('A1:G1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /* membuat header tabel */
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO AGENDA");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "NOMOR SPT");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "TGL SPT");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "PERIHAL");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "OPERATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "TGL/JAM ENTRY");

        /* Apply style header yang telah dibuat tadi ke masing-masing kolom header */
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_header);

        $laporan= $this->LaporanSpt_model->getAllForExport($this->session->userdata('tgl_awal'), $this->session->userdata('tgl_akhir'));
        $no = 1;
        $numrow = 4; // set baris pertama dari isi tabel ke baris 4
        foreach ($laporan as $row) :
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $row->nomor_agenda);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $row->nomor_spt);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row->tgl_spt);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row->perihal);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row->operator);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row->created_at);

            /* Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel) */
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        endforeach;

        /* set lebar kolom */
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(100);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        /* Set tinggi semua kolom menjadi auto (mengikuti tinggi isi dari kolommnya, jadi otomatis) */
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        /* Set orientasi kertas jadi LANDSCAPE */
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        /* Set judul file excel nya */
        $excel->getActiveSheet(0)->setTitle("Rekap Laporan SPT");
        $excel->setActiveSheetIndex(0);

        /* Proses file excel */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan SPT.xlsx"');
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function reset() {
        $this->session->unset_userdata('tgl_awal');
        $this->session->unset_userdata('tgl_akhir');
        redirect('LaporanSPT');
    }

}
