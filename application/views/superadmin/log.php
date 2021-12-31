<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Rekap Log User
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('Home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Rekap Log User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header">
                        <!-- Date range -->
                        <div class="form-group">
                            <label>Range Tanggal:</label>
                            <form method="post" action="<?= site_url('Log/search') ?>" class="form-inline">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="tgl_awal" required>
                                </div>
                                <label>&nbsp;&nbsp;s/d&nbsp;&nbsp;</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="tgl_akhir" required>
                                </div>
                        </div>
                        <div class="input-group">
                            <input class="btn btn-success" type="submit" name="submit" value="Filter">&nbsp;&nbsp;
                            <a href="<?= site_url('log/reset') ?>" class="btn btn-danger" title="Reset Pencarian">Reset</a>
                        </div>
                        </form>
                        <span class="pull-right">
                            <a href="<?= site_url('log/export') ?>" class="btn bg-olive btn-flat" title="Export ke Excel"><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export Excel</a>
                        </span>
                    </div>
                    <!-- /.box-header -->
                </div>
            </div>

            <div class="box-body">
                <h5>Result(s) : <?= $total_rows . ' data' ?></h5>
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>#</center>
                                </th>
                                <th>
                                    <center>Username</center>
                                </th>
                                <th>
                                    <center>Modul</center>
                                </th>
                                <th>
                                    <center>Tipe</center>
                                </th>
                                <th>
                                    <center>Aktifitas</center>
                                </th>
                                <th>
                                    <center>Tgl</center>
                                </th>
                                <th>
                                    <center>Jam</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($total_rows)) { ?>
                                <tr>
                                    <td align='center' colspan="12">
                                        <span class="text text-danger">
                                            <h5>- Data tidak ditemukan -</h5>
                                        </span>
                                    </td>
                                </tr>
                            <?php }  ?>
                            <?php
                            foreach ($all_log as $row) : ?>
                                <tr>
                                    <td align='center'><?= ++$start ?></td>
                                    <td align='center'><?= $row->username ?></td>
                                    <td align='center'><?= $row->modul ?></td>
                                    <td align='center'><?= $row->tipe ?></td>
                                    <td><?= $row->aktivitas ?></td>
                                    <td align='center'><?php $tgl = mediumdate_indo($row->created_at);
                                                        echo $tgl; ?></td>
                                    <td align='center'><?= $row->created_time ?></td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <?= $pagination ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->