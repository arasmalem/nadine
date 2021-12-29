<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Surat Keputusan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Surat Keputusan</li>
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
                <button class="btn btn-success" data-toggle="modal" data-target="#newSK"><i class="fa fa-plus"></i> Tambah</button>
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
                                <a href="<?= site_url('sk/reset') ?>" class="btn btn-sm bg-navy pull-right" style="margin-right:5px;">
                                    Reset Pencarian
                                </a>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="true">
                                <div class="box-body">
                                    <form method="post" action="<?= site_url('sk/search') ?>">
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
                                            <label for="nomor_sk">Nomor SK</label>
                                            <input type="text" class="form-control" name="nomor_sk" id="nomor_sk">
                                        </div>
                                        <div class=" form-group">
                                            <label for="perihal">Perihal</label>
                                            <input type="text" class="form-control" name="perihal" id="perihal">
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
                                    <center>File SK</center>
                                </th>
                                <th>
                                    <center>Nomor Agenda</center>
                                </th>
                                <th>
                                    <center>Nomor SK</center>
                                </th>
                                <th>
                                    <center>Tgl SK</center>
                                </th>
                                <th>
                                    <center>Perihal</center>
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
                                    <td align='center' colspan="12"><span class="text text-danger">
                                            <h5>- tidak ada data -</h5>
                                        </span></td>
                                </tr>
                            <?php } ?>
                            <?php
                            foreach ($sk as $row) :
                                $tgl = mediumdate_indo($row->tgl_sk);
                            ?>
                                <tr>
                                    <td align='center'><?= ++$start ?></td>
                                    <?php if ($row->file_surat == '' or $row->file_surat == NULL) { ?>
                                        <td></td>
                                    <?php
                                    } else {
                                    ?>
                                        <td align='center'>
                                            <a href="<?= base_url('files_sk/') . $row->file_surat ?>" target="_blank">
                                                <center><img src="<?= site_url('assets/') ?>images/pdf.png" width='15' height='15'></center>
                                            </a>
                                        </td>
                                    <?php } ?>
                                    <td align='center'><?= $row->nomor_agenda ?></td>
                                    <td align='center'><?= $row->nomor_sk ?></td>
                                    <td align='center'><?= $tgl ?></td>
                                    <td><?= $row->perihal ?></td>
                                    <td align='center'>
                                        <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#editSK<?= $row->id ?>" title="Edit"></a>&nbsp;
                                        <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delSK<?= $row->id; ?>" title="Delete"></a>&nbsp;
                                        <a href="" class="text text-success fa fa-search" data-toggle="modal" data-target="#detailSK<?= $row->id; ?>" title="Detail"></a>
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

<!-- Modal Tambah SK -->
<div class="modal fade" id="newSK" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tambah Surat Keputusan</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('SK/add'); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <div class="form-group">
                        <label>Klasifikasi Surat</label>
                        <select class="form-control select2" name="klasifikasi" style="width: 100%;" required>
                            <option value="" selected="selected" disabled>- Pilih Klasifikasi Surat -</option>
                            <?php foreach ($klasifikasi as $row) : ?>
                                <option value="<?= $row->kode_surat ?>"><?= $row->kode_surat . ' - ' . $row->klasifikasi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bidang</label>
                        <select class="form-control" name="bidang" required>
                            <option value="" selected disabled>- Pilih Bidang -</option>
                            <?php foreach ($bidang as $b) : ?>
                                <option value="<?= $b->kode ?>"><?= $b->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tgl SK</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" name="tgl_sk" value="<?= date('Y-m-d') ?>" readonly />
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

<!-- Modal Edit SK -->
<?php foreach ($sk as $row) :
    $sk_id = $row->id;
?>
    <div class="modal fade" id="editSK<?= $sk_id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit Surat Keputusan</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('sk/edit/') . $sk_id; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <input type="hidden" name="old_file" value="<?= $row->file_surat ?>">
                        <div class="form-group">
                            <label for="nomor_agenda">Nomor Agenda SK</label>
                            <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" value="<?= $row->nomor_agenda ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nomor_sk">Nomor SK</label>
                            <input type="text" class="form-control" id="nomor_sk" name="nomor_sk" value="<?= $row->nomor_sk ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tgl SK</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="tgl_sk" value="<?= $row->tgl_sk ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi Surat</label>
                            <select class="form-control select2" name="klasifikasi" style="width: 100%;" required>
                                <?php foreach ($klasifikasi as $k) : ?>
                                    <?php if ($row->klasifikasi == $k->kode_surat) { ?>
                                        <option value="<?= $k->kode_surat ?>" selected><?= $k->kode_surat . ' - ' . $k->klasifikasi ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $k->kode_surat ?>"><?= $k->kode_surat . ' - ' . $k->klasifikasi ?></option>
                                <?php }
                                endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <select class="form-control" name="bidang">
                                <?php $bid = explode('/', $row->nomor_sk); ?>
                                <?php foreach ($bidang as $b) : ?>
                                    <?php if ($bid[2] == $b->kode) { ?>
                                        <option value="<?= $b->kode ?>" selected><?= $b->name ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $b->kode ?>"><?= $b->name ?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <textarea class="form-control" rows="3" id="perihal" name="perihal" required><?= $row->perihal ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="file">File Surat</label>
                            <input type="file" id="file" name="file">

                            <p class="help-block" style="color:red;">Kosongkan jika file surat tidak diganti! Ukuran file maksimal 1 MB dan bertipe pdf</p>
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

<!-- Modal Hapus SK -->
<?php
foreach ($sk as $row) :
    $sk_id = $row->id;
?>
    <div class="modal fade" id="delSK<?= $sk_id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <a href="<?= site_url('sk/delete/') . $sk_id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal View Detail SK -->
<?php
foreach ($sk as $row) :
    $sk_id = $row->id;
?>
    <div class="modal fade" id="detailSK<?= $sk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Informasi Detail Surat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Nomor Agenda</label>
                            <div class="col-sm-9">
                                <label class="col-sm-4"><?= $row->nomor_agenda ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Nomor SK</label>
                            <div class="col-sm-9">
                                <label class="col-sm-12"><?= $row->nomor_sk ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Tgl SK</label>
                            <div class="col-sm-9">
                                <label class="col-sm-12"><?= mediumdate_indo($row->tgl_sk) ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Klasifikasi Surat</label>
                            <div class="col-sm-9">
                                <label class="col-sm-12"><?= $row->klasifikasi ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Perihal</label>
                            <div class="col-sm-9">
                                <label class="col-sm-12"><?= $row->perihal ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Operator</label>
                            <div class="col-sm-9">
                                <label class="col-sm-12"><?= $row->operator ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Tgl/Jam Entry</label>
                            <div class="col-sm-9">
                                <?php $tgl_entry = explode(' ', $row->created_at); ?>
                                <label class="col-sm-12"><?= mediumdate_indo($tgl_entry[0]) . ' / ' . $tgl_entry[1] ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text text-danger">Tgl/Jam Edit</label>
                            <div class="col-sm-9">
                                <?php $tgl_edit = explode(' ', $row->updated_at); ?>
                                <label class="col-sm-12"><?= mediumdate_indo($tgl_edit[0]) . ' / ' . $tgl_edit[1] ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>