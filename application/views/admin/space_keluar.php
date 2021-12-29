<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Space Nomor Surat Keluar
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Space Nomor Surat Keluar</li>
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
                    <button class="btn btn-success" data-toggle="modal" data-target="#newSpaceSuratKeluar"><i class="fa fa-plus"></i> Tambah</button>
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
                                <a href="<?= site_url('spacekeluar/reset') ?>" class="btn btn-sm bg-navy pull-right" style="margin-right:5px;">
                                    Reset Pencarian
                                </a>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="true">
                                <div class="box-body">
                                    <form method="post" action="<?= site_url('spacekeluar/search') ?>">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                        <div class="form-group">
                                            <label>Tanggal Surat</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right datepicker" name="tgl">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nomor_agenda">Nomor Agenda</label>
                                            <input type="text" class="form-control" name="nomor_agenda" id="nomor_agenda">
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
                                    <center>Nomor Agenda Surat Keluar</center>
                                </th>
                                <th>
                                    <center>Tgl Surat</center>
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
                            <?php } else { ?>
                                <?php
                                foreach ($spaceKeluar as $row) :
                                    $tgl = mediumdate_indo($row->tgl);
                                ?>
                                    <tr>
                                        <td align='center'><?= ++$start ?></td>
                                        <td align='center'><?= $row->nomor_agenda ?></td>
                                        <td align='center'><?= $tgl ?></td>
                                        <td align='center'>
                                            <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#useSpaceSuratKeluar<?= $row->id ?>" title="Gunakan Nomor"></a>&nbsp;
                                            <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delSpaceSuratKeluar<?= $row->id; ?>" title="Delete"></a>
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

<!-- Modal Tambah Space Surat Keluar -->
<div class="modal fade" id="newSpaceSuratKeluar" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tambah Space Surat Keluar</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('spacekeluar/add'); ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <div class="form-group">
                        <label>Tgl Surat</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" name="tgl_surat" value="<?= date('Y-m-d') ?>" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_space_keluar">Jumlah Space Nomor Surat Keluar</label>
                        <input type="text" class="form-control" id="no_space_keluar" name="no_space_keluar" required />
                        <small class="text text-danger">*) Maksimal 10 nomor per hari ini</small>
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

<!-- Modal Gunakan Space Surat Keluar -->
<?php foreach ($spaceKeluar as $row) :
    $surat_id = $row->id;
?>
    <div class="modal fade" id="useSpaceSuratKeluar<?= $surat_id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Tambah Surat Keluar</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('spacekeluar/used/') . $surat_id ?>" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <div class="form-group">
                            <label for="nomor_agenda">Nomor Agenda</label>
                            <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" value="<?= $row->nomor_agenda ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi Surat</label>
                            <select class="form-control select2" name="klasifikasi" style="width: 100%;" required>
                                <option value="" selected="selected" disabled>- Pilih Klasifikasi Surat -</option>
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
                                    <option value="<?= $b->kode ?>"><?= $b->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan Surat</label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label>Tgl Surat</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="tgl" value="<?= $row->tgl ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sifat Surat</label>
                            <select class="form-control" name="sifat" required>
                                <option value="" selected disabled>- Pilih Sifat Surat -</option>
                                <?php foreach ($sifat as $row) : ?>
                                    <option value="<?= $row->id ?>"><?= $row->id . ' - ' . $row->sifat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input type="text" class="form-control" id="lampiran" name="lampiran">
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal</label>
                            <textarea class="form-control" rows="3" id="perihal" name="perihal" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ringkasan">Ringkasan</label>
                            <textarea class="form-control" rows="3" id="ringkasan" name="ringkasan" required></textarea>
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

<!-- Modal Hapus Surat Keluar -->
<?php
foreach ($spaceKeluar as $row) :
    $surat_id = $row->id;
?>
    <div class="modal fade" id="delSpaceSuratKeluar<?= $surat_id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <a href="<?= site_url('spacekeluar/delete/') . $surat_id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>