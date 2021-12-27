<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Space Nomor SK
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Space Nomor SK</li>
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
                <?php if ($this->session->userdata('role') != 'User') { ?>
                    <button class="btn btn-success" data-toggle="modal" data-target="#newSpaceSK"><i class="fa fa-plus"></i> Tambah</button>
                <?php } ?>
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
                                <a href="<?= site_url('spacesk/reset') ?>" class="btn btn-sm bg-navy pull-right" style="margin-right:5px;">
                                    Reset Pencarian
                                </a>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="true">
                                <div class="box-body">
                                    <form method="post" action="<?= site_url('spacesk/search') ?>">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                        <div class="form-group">
                                            <label>Tanggal SK</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right datepicker" name="tgl_sk">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nomor_agenda">Nomor Agenda SK</label>
                                            <input type="text" class="form-control" name="nomor_agenda">
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
                                    <center>Nomor Agenda SK</center>
                                </th>
                                <th>
                                    <center>Tgl SK</center>
                                </th>
                                <th>
                                    <center>Actions</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($total_rows == 0) { ?>
                                <tr>
                                    <td align='center' colspan="12"><span class="text text-danger">
                                            <h5>- tidak ada data -</h5>
                                        </span></td>
                                </tr>
                                <?php } else {
                                foreach ($spacesk as $row) :
                                    $tgl = mediumdate_indo($row->tgl_sk);
                                ?>
                                    <tr>
                                        <td align='center'><?= ++$start ?></td>
                                        <td align='center'><?= $row->nomor_agenda ?></td>
                                        <td align='center'><?= $tgl ?></td>
                                        <td align='center'>
                                            <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#useSpaceSK<?= $row->id ?>" title="Gunakan Nomor"></a>&nbsp;
                                            <?php if ($this->session->userdata('username') != 'picsurat') { ?>
                                                <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delSpaceSK<?= $row->id; ?>" title="Delete"></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            }
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

<!-- Modal Tambah Space SK -->
<div class="modal fade" id="newSpaceSK" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Space Nomor SK</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('spacesk/add'); ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <div class="form-group">
                        <label>Tgl SK</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" name="tgl_sk" value="<?= date('Y-m-d') ?>" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_space_sk">Jumlah Space Nomor SK</label>
                        <input type="text" class="form-control" id="no_space_sk" name="no_space_sk" required />
                        <small class="text text-danger">*) Maksimal 5 nomor per hari ini</small>
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

<!-- Modal Gunakan Space SK -->
<?php foreach ($spacesk as $row) : ?>
    <div class="modal fade" id="useSpaceSK<?= $row->id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Surat Keputusan</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('spacesk/used/') . $row->id ?>" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <div class="form-group">
                            <label for="nomor_agenda">Nomor Agenda SK</label>
                            <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" value="<?= $row->nomor_agenda ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi Surat</label>
                            <select class="form-control select2" name="klasifikasi" style="width: 100%;" required>
                                <option value="" selected disabled>- Pilih Klasifikasi Surat -</option>
                                <?php foreach ($klasifikasi as $k) : ?>
                                    <option value="<?= $k->kode_surat ?>"><?= $k->kode_surat . ' - ' . $k->klasifikasi ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <select class="form-control" name="bidang" required>
                                <option value="" selected disabled>- Pilih Bidang -</option>
                                <?php foreach ($bidang as $b) : ?>
                                    <option value="<?= $b->kode_bidang ?>"><?= $b->nama_bidang ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tgl SK</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="tgl_sk" value="<?= $row->tgl_sk ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <textarea class="form-control" rows="3" id="perihal" name="perihal" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="file">File Surat</label>
                            <input type="file" id="file" name="file">

                            <p class="help-block" style="color:red;">File surat harus diupload! Ukuran file maksimal 1 MB dan bertipe pdf</p>
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
<?php endforeach; ?>

<!-- Modal Hapus SK -->
<?php
foreach ($spacesk as $row) : ?>
    <div class="modal fade" id="delSpaceSK<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <a href="<?= site_url('spacesk/delete/') . $row->id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>