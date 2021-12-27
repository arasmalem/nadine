<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>version 1.0</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('Home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
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
                        <span class="info-box-text">Surat Masuk</span>
                        <span class="info-box-number"></span>
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
                    <span class="info-box-icon bg-maroon"><i class="fa fa-file-archive-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Surat Rahasia</span>
                        <span class="info-box-number"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="glyphicon glyphicon-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Undangan</span>
                        <span class="info-box-number"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-6">
                <!-- BAR CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Jumlah Surat Masuk dan Keluar per Tahun</h3>

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
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Statistik Disposisi Surat Masuk</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="chart-responsive">
                                    <canvas id="pieChart" height="150"></canvas>
                                </div>
                                <!-- ./chart-responsive -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4">
                                <ul class="chart-legend clearfix">
                                    <li><i class="fa fa-circle-o text-red"></i> Belum Disposisi</li>
                                    <li><i class="fa fa-circle-o text-blue"></i> Sudah Disposisi</li>
                                </ul>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                    <!-- <div class="box-footer no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#">United States of America <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                            <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a></li>
                        </ul>
                    </div> -->
                    <!-- /.footer -->
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->