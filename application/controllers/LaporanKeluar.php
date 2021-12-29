<?php defined('BASEPATH') or exit('No direct script access allowed');

class LaporanKeluar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('LaporanKeluar_model');
    }

    public function index() {
        /* PAGINATION */
        // config
        $total_rows = $this->LaporanKeluar_model->getTotalRows();
        $config['base_url'] = base_url() . '/laporankeluar/index';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $laporan = $this->LaporanKeluar_model->getAll($config['per_page'], $this->uri->segment(3));

        $data = [
            'title' => 'Laporan Surat Keluar',
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
        $this->load->view('superadmin/laporan_keluar');
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
        $total_rows = $this->LaporanKeluar_model->getTotalRowsBySearch($data['tgl_awal'], $data['tgl_akhir']);
        $config['base_url'] = base_url() . '/laporankeluar/search';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 7;

        // initialize
        $this->pagination->initialize($config);

        /* END OF PAGINATION */

        $user = $this->Users_model->getByUsername($this->session->userdata('username'));
        $laporan = $this->LaporanKeluar_model->getAll($config['per_page'], $this->uri->segment(3), $data['tgl_awal'], $data['tgl_akhir']);

        $data = [
            'title' => 'Laporan Surat Keluar',
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
        $this->load->view('superadmin/laporan_keluar');
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
                               ->setTitle('Rekap Laporan Surat Keluar')
                               ->setSubject('Laporan')
                               ->setDescription('Rekap Laporan Surat Keluar')
                               ->setKeywords('Laporan Surat Keluar');

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
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "REKAP LAPORAN SURAT KELUAR");
        $excel->getActiveSheet()->mergeCells('A1:K1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /* membuat header tabel */
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO AGENDA");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "TUJUAN SURAT");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NOMOR SURAT");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "TGL SURAT");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "SIFAT");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "KLASIFIKASI");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "PERIHAL");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "RINGKASAN");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "OPERATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "TGL/JAM ENTRY");

        /* Apply style header yang telah dibuat tadi ke masing-masing kolom header */
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_header);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_header);

        $laporan= $this->LaporanKeluar_model->getAllForExport($this->session->userdata('tgl_awal'), $this->session->userdata('tgl_akhir'));
        $no = 1;
        $numrow = 4; // set baris pertama dari isi tabel ke baris 4
        foreach ($laporan as $row) :
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $row->nomor_agenda);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $row->tujuan);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row->nomor_surat_keluar);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row->tgl);

            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row->sifat);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row->klasifikasi);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $row->perihal);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $row->ringkasan);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $row->operator);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $row->created_at);

            /* Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel) */
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        endforeach;

        /* set lebar kolom */
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(75);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(75);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

        /* Set tinggi semua kolom menjadi auto (mengikuti tinggi isi dari kolommnya, jadi otomatis) */
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        /* Set orientasi kertas jadi LANDSCAPE */
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        /* Set judul file excel nya */
        $excel->getActiveSheet(0)->setTitle("Rekap Laporan Surat Keluar");
        $excel->setActiveSheetIndex(0);

        /* Proses file excel */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Surat Keluar.xlsx"');
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function reset() {
        $this->session->unset_userdata('tgl_awal');
        $this->session->unset_userdata('tgl_akhir');
        redirect('LaporanKeluar');
    }

}
