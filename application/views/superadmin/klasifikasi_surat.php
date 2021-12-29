<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Klasifikasi Surat
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Klasifikasi Surat</li>
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

        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <button class="btn btn-success" data-toggle="modal" data-target="#newKlasifikasi
                " title="Tambah Klasifikasi Surat"><i class="fa fa-plus"></i> Tambah</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel box box-danger">
                            <div class="box-header">
                                <button data-toggle="collapse" data-parent="#accordion" data-target="#collapseThree" class="btn btn-sm btn-danger pull-right" aria-expanded="true">
                                    Filter Pencarian
                                </button>
                                <a href="<?= site_url('klasifikasisurat/reset') ?>" class="btn btn-sm bg-navy pull-right" style="margin-right:5px;">
                                    Reset Pencarian
                                </a>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="true">
                                <div class="box-body">
                                    <form method="post" action="<?= site_url('klasifikasisurat/search') ?>">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                        <div class="form-group">
                                            <label for="kode_surat">Kode Surat</label>
                                            <input type="text" class="form-control" name="kode_surat" id="kode_surat">
                                        </div>
                                        <div class=" form-group">
                                            <label for="klasifikasi">Klasifikasi Surat</label>
                                            <input type="text" class="form-control" name="klasifikasi" id="klasifikasi">
                                        </div>
                                        <button type="reset" class="btn btn-default" title="Hapus Pencarian">Clear</button>
                                        <input type="submit" name="submit" class="btn btn-success" value="Search" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <h5>Result(s) : <?= $total_rows . ' data' ?></h5>
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>#</center>
                                </th>
                                <th>
                                    <center>Kode Surat</center>
                                </th>
                                <th>
                                    <center>Klasifikasi Surat</center>
                                </th>
                                <th>
                                    <center>Actions</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($total_rows)) { ?>
                                <tr>
                                    <td align='center' colspan="12">
                                        <span class="text text-danger">
                                            <h5>- tidak ada data -</h5>
                                        </span>
                                    </td>
                                </tr>
                            <?php }  ?>
                            <?php
                            foreach ($klasifikasi as $row) : ?>
                                <tr>
                                    <td align='center'><?= ++$start ?></td>
                                    <td align='center'><?= $row->kode_surat ?></td>
                                    <td align='center'><?= $row->klasifikasi ?></td>
                                    <td align='center'>
                                        <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#editKlasifikasi<?= $row->id ?>" title="Edit"></a>&nbsp;
                                        <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delKlasifikasi<?= $row->id; ?>" title="Delete"></a>&nbsp;
                                    </td>
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

<!-- Modal Tambah Klasifikasi Surat -->
<div class="modal fade" id="newKlasifikasi" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tambah Klasifikasi Surat</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('KlasifikasiSurat/add'); ?>">
                    <div class="form-group">
                        <label for="kode">Kode Surat</label>
                        <input type="text" class="form-control" id="kode" name="kode" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="klasifikasi">Klasifikasi Surat</label>
                        <input type="text" class="form-control" id="klasifikasi" name="klasifikasi" required autocomplete="off">
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

<!-- Modal Edit Klasifikasi -->
<?php foreach ($klasifikasi as $row) : ?>
    <div class="modal fade" id="editKlasifikasi<?= $row->id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit Klasifikasi Surat</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('KlasifikasiSurat/edit/') . $row->id; ?>">
                        <div class="form-group">
                            <label for="kode">Kode Surat</label>
                            <input type="text" class="form-control" id="kode" name="kode" value="<?= $row->kode_surat ?>" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="klasifikasi">Klasifikasi Surat</label>
                            <input type="text" class="form-control" id="klasifikasi" name="klasifikasi" value="<?= $row->klasifikasi ?>" required autocomplete="off">
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

<!-- Modal Hapus Klasifikasi -->
<?php
foreach ($klasifikasi as $row) :
?>
    <div class="modal fade" id="delKlasifikasi<?= $row->id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h3 class="modal-title"><i class="fa fa-exclamation-circle"></i> Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <h4>Apakah Anda yakin ingin menghapus data ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a href="<?= site_url('KlasifikasiSurat/delete/') . $row->id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>