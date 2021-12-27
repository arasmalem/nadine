<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sifat Surat
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sifat Surat</li>
        </ol>
        <!-- Flashdata -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="flash-data" data-status="success" data-flashdata="<?= $this->session->flashdata('success') ?>"></div>
        <?php } elseif ($this->session->flashdata('failed')) { ?>
            <div class="flash-data" data-status="failed" data-flashdata="<?= $this->session->flashdata('failed') ?>"></div>
        <?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="col-md-6">
            <!-- Default box -->
            <div class="box">
                <div class="box-header">
                    <button class="btn btn-success" data-toggle="modal" data-target="#newSifat
                    " title="Tambah Sifat Surat"><i class="fa fa-plus"></i> Tambah</button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>#</center>
                                    </th>
                                    <th>
                                        <center>Sifat Surat</center>
                                    </th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($sifat as $row) :
                                ?>
                                    <tr>
                                        <td align='center'><?= $no++ ?></td>
                                        <td align='center'><?= $row->sifat ?></td>
                                        <td align='center'>
                                            <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#editSifat<?= $row->id ?>" title="Edit"></a>&nbsp;
                                            <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delSifat<?= $row->id; ?>" title="Delete"></a>&nbsp;
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Tambah Sifat Surat -->
<div class="modal fade" id="newSifat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Sifat Surat</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('SifatSurat/add'); ?>">
                    <div class="form-group">
                        <label for="sifat">Sifat Surat</label>
                        <input type="text" class="form-control" id="sifat" name="sifat" required autocomplete="off">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Sifat -->
<?php foreach ($sifat as $row) : ?>
    <div class="modal fade" id="editSifat<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Sifat Surat</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('SifatSurat/edit/') . $row->id; ?>">
                        <div class="form-group">
                            <label for="sifat">Sifat Surat</label>
                            <input type="text" class="form-control" id="sifat" name="sifat" value="<?= $row->sifat ?>" required autocomplete="off">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Hapus Sifat -->
<?php
foreach ($sifat as $row) :
?>
    <div class="modal fade" id="delSifat<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><i class="fa fa-exclamation-circle"></i> Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <h4>Apakah Anda yakin ingin menghapus data ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a href="<?= site_url('SifatSurat/delete/') . $row->id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>