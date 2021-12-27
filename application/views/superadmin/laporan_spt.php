<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Laporan SPT
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Laporan SPT</li>
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
                            <label>Range Tanggal Entry:</label>
                            <form method="post" action="<?= site_url('laporanspt/search') ?>" class="form-inline">
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
                            <a href="<?= site_url('laporanspt/reset') ?>" class="btn btn-danger" title="Reset Pencarian">Reset</a>
                        </div>
                        </form>
                        <span class="pull-right">
                            <a href="<?= site_url('laporanspt/export') ?>" class="btn bg-olive btn-flat" title="Export ke Excel"><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export Excel</a>
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
                                    <center>Nomor Agenda</center>
                                </th>
                                <th>
                                    <center>Nomor SPT</center>
                                </th>
                                <th>
                                    <center>Tgl SPT</center>
                                </th>
                                <th>
                                    <center>Perihal</center>
                                </th>
                                <th>
                                    <center>Operator</center>
                                </th>
                                <th>
                                    <center>Tgl/Jam Entry</center>
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
                            foreach ($laporan as $row) : ?>
                                <tr>
                                    <td align='center'><?= ++$start ?></td>
                                    <td align='center'><?= $row->nomor_agenda ?></td>
                                    <td align='center'><?= $row->nomor_spt ?></td>
                                    <td align='center'>
                                        <?php
                                            $tgl = mediumdate_indo($row->tgl_spt);
                                            echo $tgl;
                                            ?>
                                    </td>
                                    <td align='center'><?= $row->perihal ?></td>
                                    <td><?= $row->operator ?></td>
                                    <td align="center"><?= $row->created_at ?></td>
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