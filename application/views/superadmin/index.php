<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>version 1.0</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Nota Dinas</span>
                        <span class="info-box-number"><?= $jmlNotaDinas ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-paper-plane"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Surat Keluar</span>
                        <span class="info-box-number"><?= $jmlSrtKeluar ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-file-text"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Surat Perintah Tugas</span>
                        <span class="info-box-number"><?= $jmlSpt ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-file"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Surat Keputusan</span>
                        <span class="info-box-number"><?= $jmlSk ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number"><?= $jmlUser ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-8">
                <!-- BAR CHART -->
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Jumlah Naskah Dinas per Tahun</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChart" style="height:230px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- TABLE: LATEST ACTIVITY -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Aktifitas Terakhir Pengguna</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Menu</th>
                                        <th>Tipe</th>
                                        <th>Aktifitas</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($latest_log as $row) : ?>
                                        <tr>
                                            <td><?= $row->username ?></td>
                                            <td><?= $row->modul ?></td>
                                            <td>
                                                <span class="
                                                    <?php
                                                        switch ($row->tipe) {
                                                            case 'insert':
                                                                echo 'label label-success';
                                                                break;
                                                            case 'update':
                                                                echo 'label label-primary';
                                                                break;
                                                            case 'delete':
                                                                echo 'label label-danger';
                                                                break;
                                                            /* case 'cancel':
                                                                echo 'label label-warning';
                                                                break;
                                                            case 'over':
                                                                echo 'label label-info';
                                                                break; */
                                                            default:
                                                                echo '';
                                                        }
                                                        ?>
                                                ">
                                                    <?= $row->tipe ?>
                                                </span>
                                            </td>
                                            <td><?= $row->aktivitas ?></td>
                                            <td><?php $tgl = mediumdate_indo($row->created_at); echo $tgl; ?></td>
                                            <td><?= $row->created_time ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->